<?php
namespace Rakshazi;

class GsdGenerator
{
    /**
     * Generate ld-json script with passed data and return it as string
     *
     * @param array $data Result data
     *
     * @return string
     */
    protected static function generateScript($data = [])
    {
        return '<script type="application/ld+json">'.json_encode($data).'</script>';
    }

    /**
     * Generate sitename in search results script
     * @link https://developers.google.com/search/docs/data-types/sitename
     *
     * @param string $name Your site name
     * @param string $alt_name Alternative site name
     * @param string $url Your site url
     *
     * @return string
     */
    public static function getSitename($name = '', $alt_name = '', $url = '')
    {
        return self::generateScript([
            '@context' => 'http://schema.org',
            '@type' => 'WebSite',
            'name' => $name,
            'alternateName' => $alt_name,
            'url' => $url
        ]);
    }

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

        return self::generateScript($result);
    }
    
    /**
     * Generate script for sitelinks searchbox
     * @link https://developers.google.com/search/docs/data-types/sitelinks-searchbox
     *
     * @param string $url Your site ROOT (homepage) url
     * @param string $search_url Your site search url, MUST HAVE "{search_query}" text! Example: https://example.com/search?q={search_query}
     *
     * @return string
     */
    public static function getSearchbox($url = '', $search_url = '')
    {
        return self::generateScript([
            '@context' => 'http://schema.org',
            '@type' => 'WebSite',
            'url' => $url,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $search_url,
                'query-input' => 'required name=search_query',
            ],
        ]);
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

        if (isset($item['rating']) && isset($item['review_count']) && $item['rating'] > 0 && $item['review_count'] > 0) {
            $result['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $item['rating'],
                'reviewCount' => $item['review_count'],
            ];
        }

        return self::generateScript($result);
    }
}
