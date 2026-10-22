# Optimizer @market-media

Optimizer é um componente de otimização de sites para motores de buscas e redes sociais. Simplificado e direto ao ponto
ele cria as tags e links necessários em sua ***head*** para o melhor resultado de busca e compartilhamento.

### Destaques

- Compositor simples para dados dinâmicos
- Configurações de autor e editor para Facebook (Configuração de autor e publicador para Facebook)
- Configure rapidamente os dados do TwitterCard para compartilhamento de cartões (Configure rapidamente os dados TwitterCard para cartões de
  compartilhar)
- Configure rapidamente dados OpenGraph para compartilhamento social. (Configure rapidamente os dados OpenGraph para compartilhamento
  social.)
- Adicione FacebookAdmins ou FacebookAppId e está tudo pronto (Adiciona FacebookAdmins ou FacebookAppId e tudo fica
  pronto)
- Composer ready e compatível com PSR-2 (Pronto para o compositor e compatível com PSR-2)

## Instalação

Optimizer está disponível via Composer::

```bash
"market-media/optimizer": "2.1.*"
```

ou rode

```bash
composer require market-media/optimizer
```

## Documentação

### PHP 8.5

Requer PHP 8.5 ou superior na série 8.x e a extensão SimpleXML. O Composer
deve ser executado com esse interpretador; versões anteriores do PHP deixam de ser aceitas.

Os parâmetros opcionais de `data()`, `publisher()`, `twitterCard()` e `facebook()`
aceitam `null` explicitamente, eliminando a depreciação de parâmetros implicitamente
nuláveis introduzida no PHP 8.4. As chamadas existentes com `null` continuam válidas.
`data()` e `getData()` retornam `null` antes de qualquer dado ser definido.

A revisão das depreciações e incompatibilidades do PHP 8.5 não identificou outros
usos afetados no código da biblioteca. Os dados personalizados continuam sendo
armazenados em `stdClass` por `__set()`, sem criar propriedades dinâmicas no Optimizer.

Referências: [depreciações do PHP 8.4](https://www.php.net/manual/en/migration84.deprecated.php),
[depreciações do PHP 8.5](https://www.php.net/manual/en/migration85.deprecated.php) e
[incompatibilidades do PHP 8.5](https://www.php.net/manual/en/migration85.incompatible.php).

Execute `composer test` para validar as APIs públicas, incluindo parâmetros nulos,
geração das tags e execução do exemplo. O teste trata avisos e depreciações como erros.

Para mais detalhes sobre como usar o optimizer, veja a pasta de exemplo com detalhes no diretório do componente

#### @optimize

```php
<?php

$op = new \MarketMedia\Optimizer\Optimizer();

echo $op->optimize(
    "Optimizer Happy and @MarketMedia",
    "Is a compact and easy-to-use tag creator to optimize your site",
    "https://www.upinside.com.br/MarketMedia/optimizer/example/",
    "https://www.upinside.com.br/uploads/images/2017/11/curso-de-html5-preparando-ambiente-de-trabalho-aula-02-1511276983.jpg"
)->render();
```

##### Result @optimize

````html
<title>Optimizer Happy and @MarketMedia</title>
<meta property="og:url" content="https://www.upinside.com.br/MarketMedia/optimizer/example/"/>
<meta property="og:title" content="Optimizer Happy and @MarketMedia"/>
<meta property="og:image"
      content="https://www.upinside.com.br/uploads/images/2017/11/curso-de-html5-preparando-ambiente-de-trabalho-aula-02-1511276983.jpg"/>
<meta property="og:description" content="Is a compact and easy-to-use tag creator to optimize your site"/>
<meta name="twitter:url" content="https://www.upinside.com.br/MarketMedia/optimizer/example/"/>
<meta name="twitter:title" content="Optimizer Happy and @MarketMedia"/>
<meta name="twitter:image"
      content="https://www.upinside.com.br/uploads/images/2017/11/curso-de-html5-preparando-ambiente-de-trabalho-aula-02-1511276983.jpg"/>
<meta name="twitter:description" content="Is a compact and easy-to-use tag creator to optimize your site"/>
<meta name="robots" content="index, follow"/>
<meta name="description" content="Is a compact and easy-to-use tag creator to optimize your site"/>
<meta itemprop="url" content="https://www.upinside.com.br/MarketMedia/optimizer/example/"/>
<meta itemprop="name" content="Optimizer Happy and @MarketMedia"/>
<meta itemprop="image"
      content="https://www.upinside.com.br/uploads/images/2017/11/curso-de-html5-preparando-ambiente-de-trabalho-aula-02-1511276983.jpg"/>
<meta itemprop="description" content="Is a compact and easy-to-use tag creator to optimize your site"/>
<link rel="canonical" href="https://www.upinside.com.br/MarketMedia/optimizer/example/"/>
````

#### @publisher

```php
<?php

$op = new \MarketMedia\Optimizer\Optimizer();

echo $op->publisher(
  "upinside",
  "robsonvleite"
)->render();
```

##### Result @publisher

````html

<meta property="article:publisher" content="https://www.facebook.com/upinside"/>
<meta property="article:author" content="https://www.facebook.com/robsonvleite"/>
````

#### @twitterCard

```php
<?php

$op = new \MarketMedia\Optimizer\Optimizer();

echo $op->twitterCard(
  "@robsonvleite",
  "@upinside",
  "upinside.com.br",
  "summary_large_image"
)->render();
```

##### Result @twitterCard

````html

<meta name="twitter:site" content="@robsonvleite"/>
<meta name="twitter:domain" content="upinside.com.br"/>
<meta name="twitter:creator" content="@robsonvleite"/>
<meta name="twitter:card" content="summary_large_image"/>
````

#### @openGraph

```php
<?php

$op = new \MarketMedia\Optimizer\Optimizer();

echo $op->openGraph(
  "upinside",
  "pt_BR",
  "article"
)->render();
```

##### Result @openGraph

````html

<meta property="og:type" content="article"/>
<meta property="og:site_name" content="upinside"/>
<meta property="og:locale" content="pt_BR"/>
````

## License

The MIT License (MIT). Please see [License File](https://github.com/robsonvleite/optimizer/blob/master/LICENSE) for more
information.
