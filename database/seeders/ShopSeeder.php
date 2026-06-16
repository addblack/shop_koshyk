<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Фрукти та овочі', 'slug' => 'fruits-vegetables', 'icon' => '🥦'],
            ['name' => 'Молочні продукти', 'slug' => 'dairy',             'icon' => '🥛'],
            ['name' => 'М\'ясо та риба',   'slug' => 'meat-fish',         'icon' => '🥩'],
            ['name' => 'Хліб та випічка',  'slug' => 'bakery',            'icon' => '🍞'],
            ['name' => 'Напої',            'slug' => 'beverages',         'icon' => '🧃'],
            ['name' => 'Крупи та макарони','slug' => 'grains',            'icon' => '🌾'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $products = [
            // Фрукти та овочі
            ['category_slug' => 'fruits-vegetables', 'name' => 'Яблука Голден',    'icon' => '🍎', 'price' => 45.00, 'old_price' => 55.00, 'unit' => 'кг'],
            ['category_slug' => 'fruits-vegetables', 'name' => 'Банани',           'icon' => '🍌', 'price' => 38.00, 'old_price' => null,  'unit' => 'кг'],
            ['category_slug' => 'fruits-vegetables', 'name' => 'Помідори',         'icon' => '🍅', 'price' => 52.00, 'old_price' => null,  'unit' => 'кг'],
            ['category_slug' => 'fruits-vegetables', 'name' => 'Огірки',           'icon' => '🥒', 'price' => 35.00, 'old_price' => 42.00, 'unit' => 'кг'],
            ['category_slug' => 'fruits-vegetables', 'name' => 'Картопля',         'icon' => '🥔', 'price' => 18.00, 'old_price' => null,  'unit' => 'кг'],
            ['category_slug' => 'fruits-vegetables', 'name' => 'Морква',           'icon' => '🥕', 'price' => 22.00, 'old_price' => null,  'unit' => 'кг'],
            // Молочні продукти
            ['category_slug' => 'dairy', 'name' => 'Молоко 2.5% 1л',     'icon' => '🥛', 'price' => 42.00,  'old_price' => null,   'unit' => 'шт'],
            ['category_slug' => 'dairy', 'name' => 'Сметана 20% 400г',   'icon' => '🫙', 'price' => 68.00,  'old_price' => 75.00,  'unit' => 'шт'],
            ['category_slug' => 'dairy', 'name' => 'Кефір 1% 900г',      'icon' => '🍶', 'price' => 38.00,  'old_price' => null,   'unit' => 'шт'],
            ['category_slug' => 'dairy', 'name' => 'Масло вершкове 200г','icon' => '🧈', 'price' => 95.00,  'old_price' => null,   'unit' => 'шт'],
            ['category_slug' => 'dairy', 'name' => 'Сир твердий 300г',   'icon' => '🧀', 'price' => 135.00, 'old_price' => 150.00, 'unit' => 'шт'],
            // М'ясо та риба
            ['category_slug' => 'meat-fish', 'name' => 'Куряче філе',    'icon' => '🍗', 'price' => 175.00, 'old_price' => null,   'unit' => 'кг'],
            ['category_slug' => 'meat-fish', 'name' => 'Свинина шия',    'icon' => '🥩', 'price' => 220.00, 'old_price' => 250.00, 'unit' => 'кг'],
            ['category_slug' => 'meat-fish', 'name' => 'Хек заморожений','icon' => '🐟', 'price' => 98.00,  'old_price' => null,   'unit' => 'кг'],
            // Хліб
            ['category_slug' => 'bakery', 'name' => 'Хліб білий',      'icon' => '🍞', 'price' => 28.00, 'old_price' => null, 'unit' => 'шт'],
            ['category_slug' => 'bakery', 'name' => 'Хліб чорний',     'icon' => '🫓', 'price' => 32.00, 'old_price' => null, 'unit' => 'шт'],
            ['category_slug' => 'bakery', 'name' => 'Батон нарізний',  'icon' => '🥖', 'price' => 25.00, 'old_price' => null, 'unit' => 'шт'],
            // Напої
            ['category_slug' => 'beverages', 'name' => 'Вода Моршинська 1.5л','icon' => '💧', 'price' => 28.00,  'old_price' => null,  'unit' => 'шт'],
            ['category_slug' => 'beverages', 'name' => 'Сік яблучний 1л',     'icon' => '🧃', 'price' => 55.00,  'old_price' => 62.00, 'unit' => 'шт'],
            ['category_slug' => 'beverages', 'name' => 'Кава Jacobs 200г',    'icon' => '☕', 'price' => 185.00, 'old_price' => null,  'unit' => 'шт'],
            // Крупи
            ['category_slug' => 'grains', 'name' => 'Рис довгозернистий 1кг','icon' => '🍚', 'price' => 58.00, 'old_price' => null,  'unit' => 'шт'],
            ['category_slug' => 'grains', 'name' => 'Гречка 1кг',             'icon' => '🌾', 'price' => 72.00, 'old_price' => 85.00, 'unit' => 'шт'],
            ['category_slug' => 'grains', 'name' => 'Макарони спагеті 400г',  'icon' => '🍝', 'price' => 35.00, 'old_price' => null,  'unit' => 'шт'],
        ];

        foreach ($products as $p) {
            $category = Category::where('slug', $p['category_slug'])->first();
            Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $category->id,
                    'name'        => $p['name'],
                    'slug'        => Str::slug($p['name']),
                    'icon'        => $p['icon'],
                    'price'       => $p['price'],
                    'old_price'   => $p['old_price'],
                    'unit'        => $p['unit'],
                    'stock'       => rand(10, 100),
                    'is_active'   => true,
                ]
            );
        }
    }
}
