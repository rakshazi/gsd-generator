### Google Structured Data generator

> This library has only needed for repo owner functions. If you want something else, feel free to create issue.

Generates `<script type="application/ld+json"></script>` with ld markup of passed data. See comments inside class if you want know more.

**Supported data types**:

1. [Breadcrumbs](https://developers.google.com/search/docs/data-types/breadcrumbs) `$result = \Rakshazi\GsdGenerator::getBreadcrumbs($data);`
2. [Search Gallery / Product](https://developers.google.com/search/docs/guides/search-gallery) `$result = \Rakshazi\GsdGenerator::getProduct($productData);`
