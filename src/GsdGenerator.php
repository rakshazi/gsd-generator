<?php
namespace Rakshazi;

class GsdGenerator
{
    /**
     * Generate breadcrumbs list
     * @link https://developers.google.com/search/docs/data-types/breadcrumbs
     *
     * @param array $items Array of items, structure: [
     *                                                    [
     *                                                        'position' => 1, 
     *                                                        'url' => 'http://example.com', 
     *                                                        'title' => 'Breadcrumb title', 
     *                                                        'icon' => 'http://example.com/icon.png'
     *                                                    ],
     *                                               ]
     *
     * @return string
     */
    public static function getBreadcrumbs($items = [])
    {
        $result = [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];

        foreach ($items as $item) {
            $result['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $item['position'],
                'item' => [
                    '@id' => $item['url'],
                    'name' => $item['title'],
                    'image' => $item['icon'],
                ],
            ];
        }

        return '<script type="application/ld+json">'.json_encode($result).'</script>';
    }


    /**
     * Generate Search Gallery -> Product
     *
     * @param array $item Array with product info, structure: [
     *                                                            'name' => 'Product name',
     *                                                            'image' => 'http://example.com/media/product.png',
     *                                                            'description' => 'Product short description',
     *                                                            'mpn' => '123455', //Manufacturer Part Number
     *                                                            'brand' => 'Product Brand Name',
     *                                                            'rating' => 4.9, //Product rating (max: 5.0)
     *                                                            'review_count' => 89, //Count of reviews
     *                                                            'currency' => 'USD', //Currency code
     *                                                            'price' => 9.99,
     *                                                            'condition' => 'New', //see links
     *                                                            'availability' => 'InStock', //see links
     *                                                            'seller_type' => 'Person, //Optional, default: Organization, @link https://schema.org/seller
     *                                                            'seller' => 'Example Org ltd.',
     *                                                        ]
     *
     * @return string
     */
    public static function getProduct($item = [])
    {
        $result = [
            '@context' => 'http://schema.org',
            '@type' => 'Product',
            'name' => $item['name'],
            'image' => $item['image'],
            'description' => $item['description'],
            'mpn' => $item['mpn'], //Manufacturer Part Number
            'brand' => [
                '@type' => 'Thing',
                'name' => $item['brand']
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $item['rating'],
                'reviewCount' => $item['review_count'],
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => $item['currency'],
                'price' => $item['price'],
                'itemCondition' => 'http://schema.org/'.ucfirst($item['condition']).'Condition', //@link https://schema.org/OfferItemCondition
                'availability' => 'http://schema.org/'.ucfirst($item['availability']), //@link https://schema.org/ItemAvailability
                'seller' => [
                    '@type' => (isset($item['seller_type']) ? $item['seller_type'] : 'Organization'), //@link https://schema.org/seller
                    'name' => $item['seller'],
                ],
            ],
        ];

        return '<script type="application/ld+json">'.json_encode($result).'</script>';
    }
}
