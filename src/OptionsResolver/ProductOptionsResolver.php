<?php

namespace App\OptionsResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOptionsResolver extends OptionsResolver
{
    public function configureOptions(): self
    {
        $this->define("name")->required()->allowedTypes("string");
        $this->define("description")->required()->allowedTypes("string");
        $this->define("price")->required()->allowedTypes("integer");

        return $this;
    }

    public function configureName(bool $isRequired = true): self
    {
        $this->setDefined("name")->setAllowedTypes("name", "string");

        if ($isRequired) {
            $this->setRequired("name");
        }

        return $this;
    }

    public function configureDescription(bool $isRequired = true): self
    {
        $this->setDefined("description")->setAllowedTypes("description", "string");

        if ($isRequired) {
            $this->setRequired("description");
        }

        return $this;
    }

    public function configurePrice(bool $isRequired = true): self
    {
        $this->setDefined("price")->setAllowedTypes("price", "integer");

        if ($isRequired) {
            $this->setRequired("price");
        }

        return $this;
    }
}
