<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Publisher;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'reviews',
            'order_items',
            'orders',
            'addresses',
            'customers',
            'book_tags',
            'book_categories',
            'book_authors',
            'books',
            'tags',
            'authors',
            'categories',
            'publishers',
        ] as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $now = Carbon::now();

        $this->seedPublishers($now);
        $this->seedCategories($now);
        $this->seedAuthors($now);
        $this->seedTags($now);
        $this->seedBooks($now);
        $this->seedBookRelations($now);
        $this->seedCustomers($now);
        $this->seedAddresses($now);
        $this->seedOrders($now);
        $this->seedOrderItems($now);
        $this->seedReviews($now);

        $totalRows = collect([
            'publishers',
            'categories',
            'authors',
            'tags',
            'books',
            'book_authors',
            'book_categories',
            'book_tags',
            'customers',
            'addresses',
            'orders',
            'order_items',
            'reviews',
        ])->sum(fn (string $table): int => DB::table($table)->count());

        $this->command?->info("Database seeded with {$totalRows} coherent bookstore rows.");
    }

    private function seedPublishers(Carbon $now): void
    {
        $countries = ['Guatemala', 'Mexico', 'Colombia', 'Argentina', 'Spain', 'USA', 'UK', 'Canada'];
        $rows = [];

        for ($i = 1; $i <= 100; $i++) {
            $rows[] = [
                'name' => sprintf('Editorial Horizonte %03d', $i),
                'country' => $countries[$i % count($countries)],
                'founded_year' => 1950 + ($i % 70),
                'active' => $i % 13 !== 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Publisher::query()->insert($rows);
    }

    private function seedCategories(Carbon $now): void
    {
        $roots = [
            'Ficcion',
            'No ficcion',
            'Tecnologia',
            'Historia',
            'Ciencia',
            'Negocios',
            'Arte',
            'Infantil',
            'Juvenil',
            'Academico',
            'Bienestar',
            'Referencia',
        ];

        foreach ($roots as $root) {
            Category::query()->create([
                'name' => $root,
                'slug' => Str::slug($root),
                'description' => "Categoria principal de {$root}.",
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $rootIds = Category::query()->whereNull('parent_id')->pluck('id')->all();
        $rows = [];

        for ($i = 1; $i <= 48; $i++) {
            $name = sprintf('Subcategoria %02d', $i);
            $rows[] = [
                'parent_id' => $rootIds[($i - 1) % count($rootIds)],
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Linea editorial especializada {$i}.",
                'active' => $i % 17 !== 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Category::query()->insert($rows);
    }

    private function seedAuthors(Carbon $now): void
    {
        $firstNames = ['Ana', 'Carlos', 'Lucia', 'Miguel', 'Sofia', 'Diego', 'Valeria', 'Javier', 'Elena', 'Mateo'];
        $lastNames = ['Garcia', 'Lopez', 'Martinez', 'Hernandez', 'Perez', 'Gomez', 'Diaz', 'Morales', 'Castillo', 'Vargas'];
        $countries = ['Guatemala', 'Mexico', 'Colombia', 'Argentina', 'Chile', 'Spain', 'Peru', 'Costa Rica'];
        $rows = [];

        for ($i = 1; $i <= 400; $i++) {
            $rows[] = [
                'name' => $firstNames[$i % count($firstNames)].' '.$lastNames[($i * 3) % count($lastNames)]." {$i}",
                'country' => $countries[$i % count($countries)],
                'birth_date' => Carbon::create(1945 + ($i % 45), (($i % 12) + 1), (($i % 27) + 1))->toDateString(),
                'bio' => "Autor con catalogo activo en narrativa, investigacion y divulgacion. Registro {$i}.",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Author::query()->insert($rows);
    }

    private function seedTags(Carbon $now): void
    {
        $baseNames = [
            'Premiado',
            'Lectura rapida',
            'Clasico moderno',
            'Best seller',
            'Nueva edicion',
            'Para clubes',
            'Investigacion',
            'Aprendizaje',
            'Coleccionable',
            'Recomendado',
        ];

        $rows = [];

        for ($i = 1; $i <= 80; $i++) {
            $name = $baseNames[$i % count($baseNames)].' '.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $rows[] = [
                'name' => $name,
                'slug' => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Tag::query()->insert($rows);
    }

    private function seedBooks(Carbon $now): void
    {
        $publisherIds = Publisher::query()->pluck('id')->all();
        $themes = ['memoria', 'ciudad', 'datos', 'mercado', 'frontera', 'archivo', 'mapa', 'lenguaje', 'futuro', 'raiz'];
        $formats = ['manual', 'cronica', 'novela', 'ensayo', 'guia', 'atlas', 'tratado', 'cuaderno'];
        $rows = [];

        for ($i = 1; $i <= 2000; $i++) {
            $price = random_int(799, 8999) / 100;
            $rows[] = [
                'publisher_id' => $publisherIds[array_rand($publisherIds)],
                'title' => sprintf('%s de la %s %04d', ucfirst($formats[$i % count($formats)]), $themes[($i * 7) % count($themes)], $i),
                'isbn' => '978'.str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                'description' => "Libro generado para el catalogo de laboratorio. Ejemplar {$i}.",
                'price' => $price,
                'stock' => random_int(0, 250),
                'published_at' => $now->copy()->subDays(random_int(30, 14000))->toDateString(),
                'is_active' => $i % 11 !== 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Book::query()->insert($chunk);
        }
    }

    private function seedBookRelations(Carbon $now): void
    {
        $bookIds = Book::query()->pluck('id')->all();
        $authorIds = Author::query()->pluck('id')->all();
        $categoryIds = Category::query()->pluck('id')->all();
        $tagIds = Tag::query()->pluck('id')->all();

        $bookAuthors = [];
        $bookCategories = [];
        $bookTags = [];

        foreach ($bookIds as $index => $bookId) {
            $selectedAuthors = $this->sampleIds($authorIds, ($index % 5 === 0) ? 2 : 1);
            foreach ($selectedAuthors as $position => $authorId) {
                $bookAuthors[] = [
                    'book_id' => $bookId,
                    'author_id' => $authorId,
                    'contribution_type' => $position === 0 ? 'author' : 'coauthor',
                    'royalty_percentage' => $position === 0 ? 70.00 : 30.00,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach ($this->sampleIds($categoryIds, 2 + ($index % 4 === 0 ? 1 : 0)) as $position => $categoryId) {
                $bookCategories[] = [
                    'book_id' => $bookId,
                    'category_id' => $categoryId,
                    'is_primary' => $position === 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach ($this->sampleIds($tagIds, 3 + ($index % 6 === 0 ? 1 : 0)) as $tagId) {
                $bookTags[] = [
                    'book_id' => $bookId,
                    'tag_id' => $tagId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($bookAuthors, 1000) as $chunk) {
            DB::table('book_authors')->insert($chunk);
        }

        foreach (array_chunk($bookCategories, 1000) as $chunk) {
            DB::table('book_categories')->insert($chunk);
        }

        foreach (array_chunk($bookTags, 1000) as $chunk) {
            DB::table('book_tags')->insert($chunk);
        }
    }

    private function seedCustomers(Carbon $now): void
    {
        $rows = [];

        for ($i = 1; $i <= 1500; $i++) {
            $rows[] = [
                'name' => sprintf('Cliente %04d', $i),
                'email' => sprintf('cliente%04d@example.test', $i),
                'phone' => '+502 '.random_int(2000, 7999).'-'.random_int(1000, 9999),
                'birth_date' => Carbon::create(1960 + ($i % 45), (($i % 12) + 1), (($i % 27) + 1))->toDateString(),
                'is_vip' => $i % 10 === 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Customer::query()->insert($chunk);
        }
    }

    private function seedAddresses(Carbon $now): void
    {
        $customerIds = Customer::query()->pluck('id')->all();
        $cities = ['Guatemala', 'Quetzaltenango', 'Antigua Guatemala', 'Escuintla', 'Cobán', 'Mazatenango'];
        $rows = [];

        foreach ($customerIds as $index => $customerId) {
            $rows[] = [
                'customer_id' => $customerId,
                'label' => 'home',
                'line_one' => sprintf('Avenida %d, casa %d', ($index % 20) + 1, $index + 100),
                'line_two' => null,
                'city' => $cities[$index % count($cities)],
                'state' => 'Guatemala',
                'postal_code' => str_pad((string) (($index % 9999) + 1), 5, '0', STR_PAD_LEFT),
                'country' => 'Guatemala',
                'is_primary' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($index % 3 === 0) {
                $rows[] = [
                    'customer_id' => $customerId,
                    'label' => 'work',
                    'line_one' => sprintf('Oficina %d, nivel %d', $index + 1, ($index % 12) + 1),
                    'line_two' => 'Zona comercial',
                    'city' => $cities[($index + 2) % count($cities)],
                    'state' => 'Guatemala',
                    'postal_code' => str_pad((string) (($index % 8999) + 1000), 5, '0', STR_PAD_LEFT),
                    'country' => 'Guatemala',
                    'is_primary' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('addresses')->insert($chunk);
        }
    }

    private function seedOrders(Carbon $now): void
    {
        $customerIds = Customer::query()->pluck('id')->all();
        $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['card', 'cash', 'transfer', 'wallet'];
        $rows = [];

        for ($i = 1; $i <= 2500; $i++) {
            $status = $statuses[$i % count($statuses)];
            $orderedAt = $now->copy()->subDays(random_int(0, 730))->subHours(random_int(0, 23));

            $rows[] = [
                'customer_id' => $customerIds[array_rand($customerIds)],
                'status' => $status,
                'ordered_at' => $orderedAt,
                'shipped_at' => in_array($status, ['shipped', 'delivered'], true) ? $orderedAt->copy()->addDays(random_int(1, 6)) : null,
                'total_amount' => 0,
                'payment_method' => $paymentMethods[$i % count($paymentMethods)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Order::query()->insert($chunk);
        }
    }

    private function seedOrderItems(Carbon $now): void
    {
        $orderIds = Order::query()->pluck('id')->all();
        $bookPrices = Book::query()->pluck('price', 'id')->all();
        $bookIds = array_keys($bookPrices);
        $rows = [];
        $orderTotals = array_fill_keys($orderIds, 0.0);

        foreach ($orderIds as $index => $orderId) {
            foreach ($this->sampleIds($bookIds, 1 + ($index % 4)) as $bookId) {
                $quantity = random_int(1, 4);
                $unitPrice = (float) $bookPrices[$bookId];
                $lineTotal = round($quantity * $unitPrice, 2);

                $rows[] = [
                    'order_id' => $orderId,
                    'book_id' => $bookId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $orderTotals[$orderId] += $lineTotal;
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('order_items')->insert($chunk);
        }

        foreach ($orderTotals as $orderId => $total) {
            Order::query()
                ->whereKey($orderId)
                ->update([
                'total_amount' => round($total, 2),
                'updated_at' => $now,
                ]);
        }
    }

    private function seedReviews(Carbon $now): void
    {
        $customerIds = Customer::query()->pluck('id')->all();
        $bookIds = Book::query()->pluck('id')->all();
        $rows = [];

        for ($i = 1; $i <= 3000; $i++) {
            $rating = random_int(1, 5);
            $rows[] = [
                'customer_id' => $customerIds[array_rand($customerIds)],
                'book_id' => $bookIds[array_rand($bookIds)],
                'rating' => $rating,
                'title' => $rating >= 4 ? 'Muy recomendado' : 'Lectura con observaciones',
                'body' => "Resena generada para validar relaciones y filtros del laboratorio. Registro {$i}.",
                'is_verified_purchase' => $i % 4 !== 0,
                'reviewed_at' => $now->copy()->subDays(random_int(0, 730)),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('reviews')->insert($chunk);
        }
    }

    /**
     * @param array<int, int|string> $ids
     * @return array<int, int|string>
     */
    private function sampleIds(array $ids, int $count): array
    {
        $copy = $ids;
        shuffle($copy);

        return array_slice($copy, 0, $count);
    }
}
