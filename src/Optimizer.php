<?php

namespace MarketMedia\Optimizer;

/**
 * Class MarketMedia Optimizer
 */
class Optimizer extends MetaTags
{
    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $image
     * @param bool $follow
     * @return Optimizer
     */
    public function optimize(
        string $title,
        string $description,
        string $url,
        string $image,
        bool $follow = true
    ): Optimizer {
        $this->data($title, $description, $url, $image);

        $title = $this->filter($title);
        $description = $this->filter($description);

        $this->buildTag("title", $title);
        $this->buildMeta("name", ["description" => $description]);
        $this->buildMeta("name", ["robots" => ($follow ? "index, follow" : "noindex, nofollow")]);
        $this->buildLink("canonical", $url);

        foreach ($this->tags as $meta => $prefix) {
            $this->buildMeta(
                $meta,
                [
                    "{$prefix}:title" => $title,
                    "{$prefix}:description" => $description,
                    "{$prefix}:url" => $url,
                    "{$prefix}:image" => $image
                ]
            );
        }

        $this->buildMeta(
            "itemprop",
            [
                "name" => $title,
                "description" => $description,
                "url" => $url,
                "image" => $image
            ]
        );

        return $this;
    }

    /**
     * Define as palavras-chave (keywords) da página.
     * @param string $keywords
     * @return Optimizer
     */
    public function keywords(string $keywords): Optimizer
    {
        $this->buildMeta("name", ["keywords" => $this->filter($keywords)]);
        return $this;
    }

    /**
     * Define o autor da página.
     * @param string $author
     * @return Optimizer
     */
    public function author(string $author): Optimizer
    {
        $this->buildMeta("name", ["author" => $this->filter($author)]);
        return $this;
    }

    /**
     * Define a cor do tema para navegadores mobile.
     * @param string $color (ex: #ffffff)
     * @return Optimizer
     */
    public function themeColor(string $color): Optimizer
    {
        $this->buildMeta("name", ["theme-color" => $color]);
        return $this;
    }

    /**
     * Define os atributos físicos e de acessibilidade da imagem para o Open Graph e Twitter.
     * Fundamental para o carregamento correto em redes sociais (especialmente WhatsApp e Facebook).
     * @param string $alt Texto alternativo da imagem.
     * @param int $width Largura recomendada (padrão: 1200)
     * @param int $height Altura recomendada (padrão: 630)
     * @return Optimizer
     */
    public function imageAttributes(string $alt, int $width = 1200, int $height = 630): Optimizer
    {
        $alt = $this->filter($alt);

        // Open Graph Image Attributes
        $this->buildMeta("property", [
            "og:image:alt" => $alt,
            "og:image:width" => (string)$width,
            "og:image:height" => (string)$height
        ]);

        // Twitter Image Attributes
        $this->buildMeta("name", [
            "twitter:image:alt" => $alt
        ]);

        return $this;
    }

    /**
     * @param string $fbPage
     * @param string|null $fbAuthor
     * @return Optimizer
     */
    public function publisher(string $fbPage, string $fbAuthor = null): Optimizer
    {
        $this->buildMeta(
            "property",
            [
                "article:publisher" => "https://www.facebook.com/{$fbPage}"
            ]
        );

        if ($fbAuthor) {
            $this->buildMeta(
                "property",
                [
                    "article:author" => "https://www.facebook.com/{$fbAuthor}"
                ]
            );
        }

        return $this;
    }

    /**
     * @param string $siteName
     * @param string $locale
     * @param string $schema
     * @return Optimizer
     */
    public function openGraph(string $siteName, string $locale = "pt_BR", string $schema = "article"): Optimizer
    {
        $prefix = "og";
        $siteName = $this->filter($siteName);

        $this->buildMeta(
            "property",
            [
                "{$prefix}:type" => $schema,
                "{$prefix}:site_name" => $siteName,
                "{$prefix}:locale" => $locale
            ]
        );

        return $this;
    }

    /**
     * @param string $creator
     * @param string $site
     * @param string $domain
     * @param string|null $card
     * @return Optimizer
     */
    public function twitterCard(string $creator, string $site, string $domain, string $card = null): Optimizer
    {
        $prefix = "twitter";
        $card = ($card ?? "summary_large_image");

        $this->buildMeta(
            "name",
            [
                "{$prefix}:card" => $card,
                "{$prefix}:site" => $site,
                "{$prefix}:creator" => $creator,
                "{$prefix}:domain" => $domain
            ]
        );

        return $this;
    }

    /**
     * Você deve usar UM ou OUTRO, se for usar $appid deixe o $admins em null.
     * Mas se for usar $admins, então deixe o $appid em null.
     * @param string|null $appId
     * @param array|null $admins
     * @return Optimizer
     */
    public function facebook(string $appId = null, array $admins = null): Optimizer
    {
        if ($appId) {
            $fb = $this->meta->addChild("meta");
            $fb->addAttribute("property", "fb:app_id");
            $fb->addAttribute("content", $appId);
            return $this;
        }

        if (!empty($admins) && is_array($admins)) {
            foreach ($admins as $admin) {
                $fb = $this->meta->addChild("meta");
                $fb->addAttribute("property", "fb:admins");
                $fb->addAttribute("content", $admin);
            }
        }

        return $this;
    }
}