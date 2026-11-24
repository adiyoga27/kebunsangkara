<?php

namespace Database\Seeders;

use App\Enums\Locale;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * English versions of the starter content. Records that already have an English version are left untouched.
 */
class EnglishTranslationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach (self::products() as $slug => $fields) {
            $this->translate(Product::query()->firstWhere('slug', $slug), $fields);
        }

        foreach ($this->faqs() as $question => $fields) {
            $this->translate(Faq::query()->firstWhere('question', $question), $fields);
        }

        foreach ($this->articles() as $slug => $fields) {
            $this->translate(Article::query()->firstWhere('slug', $slug), $fields);
        }
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function translate(?Model $record, array $fields): void
    {
        $translations = $record?->translations ?? [];

        if ($record === null || filled($translations[Locale::English->value] ?? null)) {
            return;
        }

        $record->update(['translations' => [...$translations, Locale::English->value => $fields]]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function products(): array
    {
        return [
            'durian-lokal-bali-pupuan' => [
                'name' => 'Local Bali Durian',
                'variety' => 'Local Bali durian',
                'price_unit' => 'per fruit',
                'short_description' => 'Local Bali durian from our farm. Thick, rich, sweet flesh with a hint of bitterness and the bold aroma of village durian.',
                'description' => "Local Bali durian from **Kebun Sangkara** is harvested when it ripens naturally on the tree. The flavor is distinctive: **rich, sweet and slightly bitter**, with a bold aroma — a favorite of true durian lovers.\n\n## Highlights\n\n- Tree-ripened, never force-ripened\n- Selected one by one before delivery\n- Replaced if rotten or unripe\n- Can be opened for you before delivery\n\n## Size & Price\n\nThe price per fruit depends on its size (usually 1.5–3 kg). Ask about today's stock and sizes via WhatsApp.",
                'meta_title' => 'Tree-Ripened Local Bali Durian – Kebun Sangkara',
            ],
            'durian-montong-bali' => [
                'name' => 'Bali Montong Durian',
                'variety' => 'Montong',
                'price_unit' => 'per kg',
                'short_description' => 'Montong durian from a Bali farm. Thick yellow flesh, small seeds, a mellow sweetness and a milder aroma.',
                'description' => "**Montong** durian is perfect if you like durian with **thick flesh, small seeds and a mellow sweetness**. Its aroma is lighter than local durian, so it also suits first-timers and guests from abroad.\n\n## How to Order\n\nClick **Order via WhatsApp** and tell us the weight you want and your delivery address.",
            ],
            'alpukat-mentega-bali' => [
                'name' => 'Butter Avocado',
                'variety' => 'Butter avocado',
                'price_unit' => 'per kg',
                'short_description' => 'Butter avocado with thick yellow flesh and a smooth, buttery texture. Great for juice, salads and toast.',
                'description' => "**Butter avocado** from our farm is picked mature, then left to ripen naturally. The flesh is **thick, creamy and savory** with a relatively small seed.\n\n## Great For\n\n- Avocado juice and smoothies\n- Salads, guacamole and toast\n- Cafes and restaurants\n\n## Storage Tips\n\nKeep at room temperature until it feels soft when pressed, then move it to the fridge so it lasts longer.",
                'meta_title' => 'Fresh Bali Butter Avocado – Kebun Sangkara',
            ],
            'manggis-bali' => [
                'name' => 'Bali Mangosteen',
                'variety' => 'Local mangosteen',
                'price_unit' => 'per kg',
                'short_description' => 'Fresh mangosteen with smooth purple skin and pure white flesh. Refreshingly sweet and tangy — the "queen of fruits".',
                'description' => "**Mangosteen** is called the *queen of fruits* for its exceptional flavor. We select mangosteens with **smooth skin, firm fruit** and pure white flesh.\n\n## How to Choose Good Mangosteen\n\n- The skin is deep purple and not hard\n- The leaves at the stem are still fresh and green\n- The number of petals at the bottom matches the number of segments inside\n\nMangosteen season is short, so order while stock lasts.",
            ],
            'paket-buah-kebun' => [
                'name' => 'Farm Fruit Package',
                'variety' => 'Mixed',
                'price_unit' => 'per package',
                'short_description' => 'A mixed package of 1 local durian, 1 kg of avocado and 1 kg of mangosteen. Perfect for families or as a gift.',
                'description' => "The **Farm Fruit Package** brings together the best of our farm: 1 local durian, 1 kg of butter avocado and 1 kg of mangosteen (subject to the harvest season).\n\nIdeal as a gift, a souvenir, or a week's worth of fruit for the family.",
            ],
            'bibit-durian-montong-okulasi' => [
                'name' => 'Budded Montong Durian Seedling',
                'variety' => 'Montong',
                'propagation_method' => 'Budding',
                'price_unit' => 'per plant',
                'short_description' => 'Montong durian seedlings budded from mother trees on our farm. Healthy stems, strong roots, ready to plant.',
                'description' => "**Budded Montong durian** seedlings are propagated from mother trees that have already fruited on our farm. Budded seedlings usually **bear fruit sooner** and keep the traits of the mother tree.\n\n## Specifications\n\n- Method: budding\n- Height: about 60–80 cm\n- Container: polybag\n\n## Bonus\n\nFree planting and care advice via WhatsApp. See also our [guide to planting budded durian seedlings](/artikel/cara-menanam-bibit-durian-okulasi).",
            ],
            'bibit-alpukat-mentega-sambung-pucuk' => [
                'name' => 'Grafted Butter Avocado Seedling',
                'variety' => 'Butter avocado',
                'propagation_method' => 'Top grafting',
                'price_unit' => 'per plant',
                'short_description' => 'Grafted butter avocado seedlings from heavily fruiting mother trees. Suitable for home gardens and orchards.',
                'description' => "**Grafted butter avocado** seedlings come from mother trees with thick, creamy fruit. Grafted seedlings inherit the best traits of the mother tree.\n\n## Specifications\n\n- Method: top grafting\n- Height: about 50–70 cm\n- Container: polybag\n\nFree planting advice via WhatsApp.",
            ],
            'bibit-manggis' => [
                'name' => 'Mangosteen Seedling',
                'variety' => 'Local mangosteen',
                'propagation_method' => 'Top grafting',
                'price_unit' => 'per plant',
                'short_description' => 'Mangosteen seedlings from mother trees on our farm. A long-lived tree that suits a long-term orchard.',
                'description' => "**Mangosteen** seedlings from mother trees with sweet fruit and smooth skin. Mangosteen is a long-lived tree, well suited to a long-term orchard investment.\n\n## Specifications\n\n- Method: top grafting\n- Height: about 40–60 cm\n- Container: polybag\n\nFree planting advice via WhatsApp.",
            ],
            'pupuk-kompos-organik' => [
                'name' => 'Organic Compost',
                'variety' => 'Solid organic fertilizer',
                'price_unit' => 'per sack',
                'short_description' => 'Compost made from organic matter for potting mixes and base fertilizing of fruit trees.',
                'description' => "**Organic compost** is made from composted organic matter. Use it in potting mixes or as a base fertilizer.\n\n## How to Use\n\n- Mix with soil when preparing the planting hole\n- Spread around the drip line, then cover with a thin layer of soil\n- Water after fertilizing\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
            'pupuk-kandang-fermentasi' => [
                'name' => 'Fermented Manure',
                'variety' => 'Solid organic fertilizer',
                'price_unit' => 'per sack',
                'short_description' => 'Fermented manure, a practical base fertilizer for gardens and potted plants.',
                'description' => "**Fermented manure** is livestock manure that has been fermented before use.\n\n## How to Use\n\n- Mix with soil as a base fertilizer\n- Keep away from the base of the trunk when top-dressing\n- Adjust the amount to the size of the plant\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
            'pupuk-organik-cair' => [
                'name' => 'Liquid Organic Fertilizer',
                'variety' => 'Liquid organic fertilizer',
                'price_unit' => 'per liter',
                'short_description' => 'Liquid organic fertilizer for follow-up feeding. Dilute with water before applying to plants.',
                'description' => "**Liquid organic fertilizer** is practical for follow-up feeding of seedlings and fruit trees.\n\n## How to Use\n\n- Dilute as directed on the package\n- Pour onto the soil around the roots\n- Apply in the morning or late afternoon\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
            'teh-kulit-manggis' => [
                'name' => 'Mangosteen Peel Tea',
                'variety' => 'Herbal drink',
                'price_unit' => 'per pack',
                'short_description' => 'A brewed drink made from dried mangosteen peel. Conveniently packed, enjoyable hot or cold.',
                'description' => "**Mangosteen peel tea** is made from dried mangosteen peel.\n\n## How to Serve\n\n- Steep in hot water for a few minutes\n- Add honey to taste\n- Store the pack tightly closed in a dry place\n\n*This product is a drink, not a medicine.*\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
            'madu-murni' => [
                'name' => 'Pure Honey',
                'variety' => 'Honey',
                'price_unit' => 'per bottle',
                'short_description' => 'Bottled pure honey for drinks, toast, or as a natural sweetener.',
                'description' => "**Pure honey** for tea, juice, toast, or as a replacement for sugar.\n\n## Storage\n\n- Store at room temperature, away from direct sunlight\n- Use a clean, dry spoon\n- Not for infants under 1 year of age\n\n*This product is a food, not a medicine.*\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
            'jahe-bubuk' => [
                'name' => 'Ginger Powder',
                'variety' => 'Herbal drink',
                'price_unit' => 'per pack',
                'short_description' => 'Ginger powder for warm drinks and cooking. Simply add hot water.',
                'description' => "**Ginger powder** is handy for warm drinks and as a cooking spice.\n\n## How to Serve\n\n- Dissolve one teaspoon in a glass of hot water\n- Add palm sugar or honey to taste\n\n*This product is a food, not a medicine.*\n\nAsk about stock, current prices and delivery costs via WhatsApp.",
            ],
        ];
    }

    /**
     * @return array<string, array{question: string, answer: string}>
     */
    private function faqs(): array
    {
        return array_map(fn (array $faq): array => ['question' => $faq[0], 'answer' => $faq[1]], [
            'Apakah buahnya benar-benar dari kebun di Bali?' => ['Does the fruit really come from a farm in Bali?', 'Yes. Our durian, avocado and mangosteen come from the Kebun Sangkara farm in Bali and are picked ripe. We do not sell imported fruit or fruit from other islands.'],
            'Kapan musim durian dan manggis di Bali?' => ['When is durian and mangosteen season in Bali?', 'Durian season in Bali usually runs from late in the year to early the next (roughly November–March), and mangosteen season is usually close to it. Avocado is available more often throughout the year. Exact timing depends on the weather, so please ask about stock via WhatsApp.'],
            'Bagaimana cara memesan buah?' => ['How do I order fruit?', 'Click "Order via WhatsApp" on this website and tell us the fruit, quantity and delivery address. We will confirm stock, the total price and the delivery schedule.'],
            'Apakah bisa diantar ke hotel atau villa?' => ['Can you deliver to a hotel or villa?', 'Yes. We deliver to homes, hotels and villas all over Indonesia. For durian, please check your hotel\'s rules first, as some hotels do not allow durian in the rooms.'],
            'Bagaimana jika buah yang diterima busuk atau rusak?' => ['What if the fruit I receive is rotten or damaged?', 'We offer a replacement guarantee. Send a photo of the fruit via WhatsApp within a few hours of receiving it, and we will replace it.'],
            'Apakah melayani pesanan dalam jumlah besar?' => ['Do you accept bulk orders?', 'Yes. We supply cafes, restaurants, hotels, juice businesses and events. Contact us via WhatsApp for special prices.'],
            'Apa bedanya bibit okulasi, sambung pucuk, dan bibit dari biji?' => ['What is the difference between budded, grafted and seed-grown seedlings?', 'Budded and grafted seedlings are propagated from mother trees that have already fruited, so they keep the same traits and usually bear fruit sooner. Seed-grown seedlings take longer, and their fruit may differ from the parent tree.'],
            'Apakah bibit bisa dikirim, dan apakah ada panduan menanam?' => ['Can seedlings be shipped, and do you provide planting guidance?', 'Yes. Seedlings are packed in polybags so they travel safely. Every purchase includes free planting and care advice via WhatsApp.'],
            'Metode pembayaran apa saja yang tersedia?' => ['Which payment methods are available?', 'You can pay by bank transfer, QRIS, or cash on delivery (COD) in certain areas. Details are provided when you order via WhatsApp.'],
        ]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function articles(): array
    {
        return [
            'cara-memilih-durian-matang-dan-manis' => [
                'title' => 'How to Choose a Ripe, Sweet Durian: 7 Proven Tips',
                'excerpt' => 'Afraid of picking the wrong durian? Follow 7 tips to choose a durian that is ripe, sweet and not rotten — by its aroma, sound, stem and thorns.',
                'body' => <<<'MD'
Buying durian can be tricky. They all look alike from the outside, but the inside can be very different: some are rich and sweet, some are unripe, and some are already rotten. Here are the tips durian farmers in Bali use to choose a ripe, sweet durian.

## 1. Smell It

A ripe durian has a **strong, fragrant aroma**, especially near the stem and along the seams of its shell. A faint aroma usually means it is not ripe yet. An overly sharp, alcohol-like smell can mean it is overripe.

## 2. Check the Stem

A fresh stem is usually brownish green and not dry. A very dry, black stem means the durian was picked a long time ago.

## 3. Squeeze the Thorn Tips

Hold two neighboring thorns and squeeze them together. On a ripe durian, **the thorns feel more flexible** and move easily. Unripe durian has hard, rigid thorns.

## 4. Tap and Listen

Gently tap the shell with a knife handle or a piece of wood. A **hollow, echoing sound** usually means the flesh is soft and ripe.

## 5. Shake the Fruit

In some varieties, ripe flesh separates slightly from the shell, so you can feel movement when you shake it.

## 6. Choose Fallen or Tree-Ripened Durian

**Tree-ripened** durian (harvested when naturally ripe) tastes far better than durian picked young and force-ripened. That is why Kebun Sangkara only sells tree-ripened durian.

## 7. Buy from a Trusted Seller

The safest way is to buy from a seller who offers a **replacement guarantee** for rotten or unripe durian, so you never lose out.

## Conclusion

Use your senses of smell, touch and hearing to choose a durian. If you would rather skip the hassle, you can [order durian straight from our farm](/buah/durian) — every fruit is hand-picked and guaranteed.
MD,
            ],
            'musim-durian-di-bali' => [
                'title' => 'Durian Season in Bali: When Is the Best Time to Go?',
                'excerpt' => 'When is durian season in Bali? See the estimated harvest months, the best durian-growing areas, and tips for getting fresh durian.',
                'body' => <<<'MD'
For durian lovers, knowing **when durian season is in Bali** is key to enjoying fresh durian at the best price. Here is a quick guide.

## Estimated Durian Season in Bali

Durian season in Bali generally runs from **late in the year to early the next** — roughly **November to March**. The harvest usually peaks between December and February. Exact timing can shift depending on rainfall and the altitude of the farm.

## Durian-Growing Areas in Bali

Several areas in Bali are known for durian:

- **Tabanan** – especially Pupuan and Penebel, with farms in the cool highlands.
- **Buleleng** – such as Munduk and its surroundings.
- **Jembrana** – in the west of Bali.

Durian from each area tastes slightly different, from rich and sweet to sweet with a touch of bitterness.

## Why Do Durian Prices Go Up and Down?

At the start and end of the season there is little durian, so prices tend to be higher. At peak harvest, stock is plentiful and prices are usually friendlier.

## Tips for Getting Fresh Durian

1. Order directly from a seller who works with farmers.
2. Ask whether the durian is **tree-ripened** or force-ripened.
3. Make sure there is a guarantee if the durian is rotten.

Want to enjoy Bali durian without visiting a farm? [See today's durian prices](/buah/durian) and order via WhatsApp.
MD,
            ],
            'cara-memilih-alpukat-manggis' => [
                'title' => 'How to Choose Ripe, Delicious Avocado and Mangosteen',
                'excerpt' => 'A practical guide to choosing creamy avocado and fresh mangosteen — so you never buy the wrong fruit.',
                'body' => <<<'MD'
Besides durian, Kebun Sangkara also harvests avocado and mangosteen. Each fruit shows ripeness differently. Here is a short guide.

## How to Choose Avocado

- **Press gently** near the stem. A ripe avocado feels slightly soft but not mushy.
- **Check the stem.** If it comes off easily and is yellowish green underneath, the avocado is ready to eat.
- Hard avocados can be kept at room temperature for 2–4 days until ripe.

## How to Choose Mangosteen

- **Deep purple skin** that gives slightly when pressed means the mangosteen is fresh. Very hard skin usually means it was picked long ago.
- **Fresh green leaves** at the top mean it was recently harvested.
- Count the **small petals at the bottom** — they match the number of segments inside.

## Conclusion

Fruit picked straight from the farm is always fresher. See [our fruit price list](/produk) and order via WhatsApp.
MD,
            ],
            'cara-menanam-bibit-durian-okulasi' => [
                'title' => 'How to Plant Budded Durian Seedlings So They Thrive',
                'excerpt' => 'A step-by-step guide to planting budded durian seedlings: choosing a site, preparing the hole, fertilizing and early care.',
                'body' => <<<'MD'
Growing your own durian is increasingly popular. With **budded seedlings** from quality mother trees, durian trees can usually fruit sooner than trees grown from seed. Here is how to plant them.

## 1. Choose the Right Site

- Durian likes **full sun** and soil that is **loose and never waterlogged**.
- Leave wide spacing, usually **8–10 meters** between trees, because durian canopies grow large.

## 2. Prepare the Planting Hole

1. Dig a hole of about **60 × 60 × 60 cm**.
2. Keep the topsoil and subsoil separate.
3. Mix the topsoil with **well-rotted manure** and let it rest for a few days before planting.

## 3. Plant the Seedling

1. Cut the polybag carefully so that **the roots and soil stay intact**.
2. Place the seedling in the center of the hole, making sure **the bud union stays above the soil surface**.
3. Fill with the soil and manure mix, press gently, then water.
4. Add a stake so the seedling does not sway in the wind.

## 4. Early Care

- **Water regularly**, especially in the dry season, but do not let water pool.
- Provide **temporary shade** in the first weeks if the sun is very harsh.
- **Remove suckers** that grow below the bud union.
- Fertilize regularly according to the age of the plant.

## Need Seedlings or Help?

Kebun Sangkara supplies [durian, avocado and mangosteen seedlings](/bibit) from mother trees on our own farm — with free planting advice via WhatsApp.
MD,
            ],
            'jenis-jenis-durian-di-bali' => [
                'title' => 'Types of Durian You Can Enjoy in Bali',
                'excerpt' => 'Get to know the durian types in Bali: local Bali durian, Montong and peeled durian. Which one suits you best?',
                'body' => <<<'MD'
Bali offers a range of durian, from native village durian to premium varieties now grown on local farms. Here are some popular types.

## Local Bali Durian

Local or village durian grows on traditional farms in Tabanan, Buleleng and Jembrana. Its hallmarks:

- **Rich, sweet flavor with a little bitterness**
- Strong aroma
- Varying fruit sizes

It is loved by "true" durian fans who enjoy complex flavors.

## Montong Durian

Montong is a variety from Thailand that is now also grown in Indonesia, including Bali. Its strengths:

- Thick, smooth flesh
- Small seeds
- Sweet flavor with a lighter aroma

Great for beginners or travelers trying durian for the first time.

## Peeled Durian

If you would rather not open the shell, **peeled durian** is a practical choice. The flesh is already separated and packed in a box, ready to eat anytime.

## Which One Suits You Best?

| Taste | Recommendation |
| --- | --- |
| Strong, bittersweet flavor | Local Bali durian |
| Mellow sweetness, light aroma | Montong durian |
| Convenience | Peeled durian |

Still unsure? Tell us your taste via WhatsApp and we will recommend the [best durian](/buah/durian) currently available.
MD,
            ],
        ];
    }
}
