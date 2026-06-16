/**
 * FreshMart — Cart AJAX Logic
 * Усі запити до сервера виконуються через Fetch API без перезавантаження сторінки.
 */

const Cart = (() => {
  // ─── Утиліти ───────────────────────────────────────────────
  const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  async function apiFetch(url, method = 'GET', body = null) {
    const opts = {
      method,
      headers: {
        'Content-Type':  'application/json',
        'Accept':        'application/json',
        'X-CSRF-TOKEN':  csrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
      },
    };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(url, opts);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  }

  // ─── Toast сповіщення ───────────────────────────────────────
  function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const icon   = type === 'success' ? '✅' : '❌';
    const toast  = document.createElement('div');
    toast.className = `toast toast--${type}`;
    toast.innerHTML = `
      <span class="toast__icon">${icon}</span>
      <span>${message}</span>
      <button class="toast__close" onclick="this.parentElement.remove()">×</button>
    `;
    container.prepend(toast);
    setTimeout(() => toast.remove(), 4000);
  }

  // ─── Лічильник у хедері ─────────────────────────────────────
  function updateBadge(count) {
    document.querySelectorAll('#cartBadge').forEach(el => {
      el.textContent = count;
      el.style.display = count > 0 ? 'flex' : 'none';
    });
  }

  // ─── Міні-кошик (dropdown) ──────────────────────────────────
  async function refreshMiniCart() {
    const body   = document.getElementById('miniCartBody');
    const footer = document.getElementById('miniCartFooter');
    if (!body) return;

    body.innerHTML = '<div class="mini-cart__loading"><i class="fas fa-spinner fa-spin"></i></div>';

    try {
      const data = await apiFetch('/cart/mini');
      body.innerHTML = data.html;
      updateBadge(data.cart_count);

      footer.innerHTML = data.cart_count > 0
        ? `<div class="mini-cart__total"><span>Разом:</span><span>${data.cart_total} ₴</span></div>
           <a href="/cart" class="btn btn--primary btn--full">Оформити замовлення</a>`
        : '';
    } catch {
      body.innerHTML = '<p style="padding:16px;color:red">Помилка завантаження</p>';
    }
  }

  function openMiniCart() {
    document.getElementById('miniCart')?.classList.add('open');
    document.getElementById('miniCartOverlay')?.classList.add('active');
    refreshMiniCart();
  }
  function closeMiniCart() {
    document.getElementById('miniCart')?.classList.remove('open');
    document.getElementById('miniCartOverlay')?.classList.remove('active');
  }

  // ─── Додати до кошика ───────────────────────────────────────
  async function addToCart(productId, qty = 1, btn = null) {
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }

    try {
      const data = await apiFetch('/cart/add', 'POST', { product_id: productId, quantity: qty });
      updateBadge(data.cart_count);
      showToast(data.message ?? 'Товар додано до кошика');

      // Показати контроль кількості на картці
      const card = document.querySelector(`.product-card[data-id="${productId}"]`);
      if (card) {
        const qtyControl = card.querySelector('.product-card__qty-control');
        const addBtn     = card.querySelector('.add-to-cart');
        if (qtyControl && addBtn) {
          qtyControl.style.display = 'flex';
          addBtn.style.display = 'none';
          const qtyEl = card.querySelector(`#qty-${productId}`);
          if (qtyEl) qtyEl.textContent = data.item?.qty ?? qty;
        }
      }
    } catch {
      showToast('Помилка. Спробуйте ще раз.', 'error');
    } finally {
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-plus"></i> До кошика'; }
    }
  }

  // ─── Оновити кількість (сторінка кошика) ────────────────────
  async function updateCartQty(productId, qty) {
    try {
      const data = await apiFetch('/cart/update', 'PATCH', { product_id: productId, quantity: qty });

      if (data.removed) {
        removeCartRow(productId);
      } else {
        const subtotalEl = document.getElementById(`subtotal-${productId}`);
        if (subtotalEl) subtotalEl.textContent = `${data.subtotal} ₴`;
      }

      updateBadge(data.cart_count);
      updateSummary(data.cart_count, data.cart_total);

      if (data.cart_count === 0) location.reload(); // показати "пустий кошик"
    } catch {
      showToast('Помилка оновлення', 'error');
    }
  }

  // ─── Видалити позицію ───────────────────────────────────────
  async function removeFromCart(productId) {
    try {
      const data = await apiFetch('/cart/remove', 'DELETE', { product_id: productId });
      removeCartRow(productId);
      updateBadge(data.cart_count);
      updateSummary(data.cart_count, data.cart_total);
      showToast(data.message ?? 'Товар видалено');

      if (data.cart_count === 0) location.reload();
    } catch {
      showToast('Помилка видалення', 'error');
    }
  }

  function removeCartRow(productId) {
    const row = document.getElementById(`cart-row-${productId}`);
    if (row) {
      row.style.transition = 'opacity .3s, transform .3s';
      row.style.opacity = '0';
      row.style.transform = 'translateX(20px)';
      setTimeout(() => row.remove(), 300);
    }
  }

  function updateSummary(count, total) {
    const countEl = document.getElementById('summaryCount');
    const totalEl = document.getElementById('summaryTotal');
    if (countEl) countEl.textContent = count;
    if (totalEl) totalEl.textContent = `${total} ₴`;
  }

  // ─── Ініціалізація слухачів ─────────────────────────────────
  function init() {
    // Відкрити/закрити міні-кошик
    document.getElementById('cartToggle')?.addEventListener('click', () => {
      const isOpen = document.getElementById('miniCart')?.classList.contains('open');
      isOpen ? closeMiniCart() : openMiniCart();
    });
    document.getElementById('miniCartClose')?.addEventListener('click', closeMiniCart);
    document.getElementById('miniCartOverlay')?.addEventListener('click', closeMiniCart);

    // Делегований обробник для каталогу
    document.addEventListener('click', e => {
      // Кнопка "До кошика"
      const addBtn = e.target.closest('.add-to-cart');
      if (addBtn) {
        const productId = parseInt(addBtn.dataset.product);
        const card      = addBtn.closest('.product-card');
        const qtyEl     = card?.querySelector(`#qty-${productId}`);
        const qty       = qtyEl ? parseInt(qtyEl.textContent) || 1 : 1;
        addToCart(productId, qty, addBtn);
        return;
      }

      // + на картці каталогу
      const plusBtn = e.target.closest('.qty-plus');
      if (plusBtn) {
        const productId = parseInt(plusBtn.dataset.product);
        const qtyEl     = document.getElementById(`qty-${productId}`);
        if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent) + 1;
        return;
      }

      // − на картці каталогу
      const minusBtn = e.target.closest('.qty-minus');
      if (minusBtn) {
        const productId = parseInt(minusBtn.dataset.product);
        const qtyEl     = document.getElementById(`qty-${productId}`);
        if (qtyEl) {
          const newQty = Math.max(1, parseInt(qtyEl.textContent) - 1);
          qtyEl.textContent = newQty;
        }
        return;
      }

      // Видалити з кошика (сторінка кошика)
      const removeBtn = e.target.closest('.cart-remove');
      if (removeBtn) {
        removeFromCart(parseInt(removeBtn.dataset.product));
        return;
      }

      // − на сторінці кошика
      const cartMinus = e.target.closest('.cart-minus');
      if (cartMinus) {
        const productId = parseInt(cartMinus.dataset.product);
        const input     = document.getElementById(`cart-qty-${productId}`);
        if (input) {
          const newQty = Math.max(0, parseInt(input.value) - 1);
          input.value = newQty;
          updateCartQty(productId, newQty);
        }
        return;
      }

      // + на сторінці кошика
      const cartPlus = e.target.closest('.cart-plus');
      if (cartPlus) {
        const productId = parseInt(cartPlus.dataset.product);
        const input     = document.getElementById(`cart-qty-${productId}`);
        if (input) {
          const newQty = Math.min(99, parseInt(input.value) + 1);
          input.value = newQty;
          updateCartQty(productId, newQty);
        }
        return;
      }
    });

    // Зміна кількості вручну (input на сторінці кошика)
    document.addEventListener('change', e => {
      if (e.target.classList.contains('qty-input')) {
        const productId = parseInt(e.target.dataset.product);
        const qty       = Math.min(99, Math.max(0, parseInt(e.target.value) || 0));
        e.target.value  = qty;
        updateCartQty(productId, qty);
      }
    });

    // Очистити кошик
    document.getElementById('clearCart')?.addEventListener('click', async () => {
      if (!confirm('Очистити весь кошик?')) return;
      const rows = document.querySelectorAll('.cart-row');
      for (const row of rows) {
        const productId = parseInt(row.dataset.product);
        await apiFetch('/cart/remove', 'DELETE', { product_id: productId });
        row.remove();
      }
      updateBadge(0);
      location.reload();
    });
  }

  return { init, showToast, addToCart, updateBadge, refreshMiniCart };
})();

document.addEventListener('DOMContentLoaded', () => Cart.init());
