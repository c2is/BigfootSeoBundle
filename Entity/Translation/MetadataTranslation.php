<?php

namespace Bigfoot\Bundle\SeoBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="bigfoot_seo_metadata_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_seo_metadata_translation", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MetadataTranslation extends AbstractPersonalTranslation
{
    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * @ORM\ManyToOne(targetEntity="Bigfoot\Bundle\SeoBundle\Entity\Metadata", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->content;
    }
}
