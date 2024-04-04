# ProductManagementAiTranslator Module

This module provides product-related AI translation functionality.

## Installation

### Install the required modules using Composer

```
composer require spryker-community/product-management-ai-translator spryker-community/open-ai-translator --no-interaction
```

This command will install 5 modules in total:
- `spryker-community/product-management-ai-translator`
- `spryker-community/ai-translator`
- `spryker-community/open-ai-translator`
- `spryker-community/open-ai-client`
- `openai-php/client`

## Configuration

### Add new `SprykerCommunity` namespace to configuration

```
# config/Shared/config_default.php

$config[KernelConstants::CORE_NAMESPACES] = [
    ...
    'SprykerCommunity',
];

```

### Configure OpenAI API token and cache control

```
# config/Shared/config_default.php

use SprykerCommunity\Shared\AiTranslator\AiTranslatorConstants;
use SprykerCommunity\Shared\OpenAiClient\OpenAiClientConstants;

$config[OpenAiClientConstants::API_TOKEN] = 'OpenAI API token'; // Sets private OpenAI API token
$config[AiTranslatorConstants::ENABLE_CACHE] = true; // Controls Redis caching for translations

```

### Configure Router

```
# src/Pyz/Zed/Router/RouterConfig.php

public function getControllerDirectories(): array
{
    ...
    $controllerDirectories[] = sprintf('%s/spryker-community/*/src/*/Zed/*/Communication/Controller/', APPLICATION_VENDOR_DIR);
    ...
}
```

### Configure frontend builder for Zed

Create a new javascript file:

```
# frontend/zed/build.js

const { mergeWithCustomize, customizeObject } = require('webpack-merge');
const oryxForZed = require('@spryker/oryx-for-zed');
const path = require('path');

const mergeWithStrategy = mergeWithCustomize({
    customizeObject: customizeObject({
        plugins: 'prepend'
    })
});

const myCustomZedSettings = mergeWithStrategy(oryxForZed.settings, {
    entry: {
        dirs: [path.resolve('./vendor/spryker-community/')], // Path for SprykerCommunity entry points
    }
});

oryxForZed.getConfiguration(myCustomZedSettings)
    .then(configuration => oryxForZed.build(configuration, oryxForZed.copyAssets))
    .catch(error => console.error('An error occurred while creating configuration', error));
```

Adjust the root `package.json` in the following way:

```
"scripts": {
    ...
    "zed": "node ./frontend/zed/build",
    ...
},
```

## Integration
### Adjust the following Twig templates on the project level:
```
# src/Pyz/Zed/ProductManagement/Presentation/Add/index.twig

{% block head_css %}
    ...
    <link rel="stylesheet" href="{{ assetsPath('css/spryker-zed-product-management-ai-translator.css') }}" />
{% endblock %}

{% block footer_js %}
    ...
    <script src="{{ assetsPath('js/spryker-zed-product-management-ai-translator.js') }}"></script>
{% endblock %}
```

```
# src/Pyz/Zed/ProductManagement/Presentation/AddVariant/index.twig

{% block head_css %}
    ...
    <link rel="stylesheet" href="{{ assetsPath('css/spryker-zed-product-management-ai-translator.css') }}" />
{% endblock %}

{% block footer_js %}
    ...
    <script src="{{ assetsPath('js/spryker-zed-product-management-ai-translator.js') }}"></script>
{% endblock %}
```
```
# src/Pyz/Zed/ProductManagement/Presentation/Edit/variant.twig

{% block head_css %}
    ...
    <link rel="stylesheet" href="{{ assetsPath('css/spryker-zed-product-management-ai-translator.css') }}" />
{% endblock %}

{% block footer_js %}
    ...
    <script src="{{ assetsPath('js/spryker-zed-product-management-ai-translator.js') }}"></script>
{% endblock %}
```
### Wire the plugins
```
<?php

namespace Pyz\Client\AiTranslator;

use SprykerCommunity\Client\AiTranslator\AiTranslatorDependencyProvider as SprykerCommunityAiTranslatorDependencyProviderAlias;
use SprykerCommunity\Client\AiTranslator\Dependency\Plugin\TranslatorEnginePluginInterface;
use SprykerCommunity\Client\OpenAiTranslator\Plugin\AiTranslator\OpenAiTranslatorEnginePlugin;

class AiTranslatorDependencyProvider extends SprykerCommunityAiTranslatorDependencyProviderAlias
{
    /**
     * @return \SprykerCommunity\Client\AiTranslator\Dependency\Plugin\TranslatorEnginePluginInterface
     */
    protected function getTranslatorEnginePlugin(): TranslatorEnginePluginInterface
    {
        return new OpenAiTranslatorEnginePlugin();
    }
}
```
### Generate transfers and build frontend
```
vendor/bin/console transfer:generate
vendor/bin/console frontend:zed:build
```
