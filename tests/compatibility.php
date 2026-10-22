<?php

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

require dirname(__DIR__) . '/vendor/autoload.php';

use MarketMedia\Optimizer\Optimizer;

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$optimizer = new Optimizer();
check($optimizer->data() === null, 'Empty data must return null.');
check($optimizer->getData() === null, 'Empty getData must return null.');
check($optimizer->data(null, null, null, null) === null, 'Null arguments must be accepted.');
check($optimizer->missing === null && !isset($optimizer->missing), 'Missing data must be readable.');
check($optimizer->render() === '', 'Empty metadata must render an empty string.');

$optimizer->custom = 'custom value';
check(isset($optimizer->custom) && $optimizer->custom === 'custom value', 'Magic properties must work.');

$result = $optimizer
    ->optimize('Título & teste', 'Descrição de teste', 'https://example.com/', 'https://example.com/image.jpg', false)
    ->keywords('php, otimização')
    ->author('Autor')
    ->themeColor('#ffffff')
    ->imageAttributes('Imagem de teste')
    ->publisher('page', null)
    ->publisher('page', 'author')
    ->twitterCard('@creator', '@site', 'example.com', null)
    ->openGraph('Site')
    ->facebook(null, null)
    ->facebook('123')
    ->facebook(null, ['456', '789']);

check($result === $optimizer, 'Fluent calls must return the same instance.');
check($optimizer->getData()->title === 'Título & teste', 'Original data must be preserved.');
check($optimizer->keywords === 'php, otimização', 'Keywords must be stored through __set.');

$html = $optimizer->render();
foreach ([
    '<title>Título &#38; teste</title>',
    '<meta name="robots" content="noindex, nofollow"/>',
    '<link rel="canonical" href="https://example.com/"/>',
    '<meta property="og:title" content="Título &#38; teste"/>',
    '<meta name="twitter:card" content="summary_large_image"/>',
    '<meta property="article:author" content="https://www.facebook.com/author"/>',
    '<meta property="fb:app_id" content="123"/>',
    '<meta property="fb:admins" content="456"/>',
    '<meta property="fb:admins" content="789"/>',
    '<meta property="og:image:width" content="1200"/>',
    '<meta name="twitter:image:alt" content="Imagem de teste"/>',
] as $expected) {
    check(str_contains($html, $expected), 'Missing expected output: ' . $expected);
}

check($optimizer->meta() instanceof SimpleXMLIterator, 'SimpleXML must remain available.');
$unsorted = $optimizer->debug(false);
rsort($unsorted);
check($optimizer->debug() === $unsorted, 'Debug output must support sorting.');

$defaults = new Optimizer();
$defaults->publisher('page')->twitterCard('@creator', '@site', 'example.com')->facebook();
check(str_contains($defaults->render(), 'summary_large_image'), 'Omitted optional arguments must work.');

ob_start();
try {
    require dirname(__DIR__) . '/example/index.php';
    $example = ob_get_contents();
} finally {
    ob_end_clean();
}
check(str_contains($example, '<title>'), 'The example must render metadata.');

echo 'Compatibility checks passed on PHP ' . PHP_VERSION . PHP_EOL;
