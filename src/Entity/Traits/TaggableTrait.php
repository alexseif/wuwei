<?php

namespace App\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Tag;
use Doctrine\ORM\Mapping as ORM;

trait TaggableTrait
{


    //#[ORM\JoinTable(name: "entity_tags", joinColumns: [#[ORM\JoinColumn(name: "entity_id", referencedColumnName: "id")]], inverseJoinColumns: [#[ORM\JoinColumn(name: "tag_id", referencedColumnName: "id")]])]
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: "entity_tags",
      joinColumns: [ORM\JoinColumn(name: 'entity_id', referencedColumnName: 'id'),],
      inverseJoinColumns: [ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    )]
    private ArrayCollection|Tag[] $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    #[ORM\return(ArrayCollection|Tag[])]
    public function getTags(): ArrayCollection|Tag[]
    {
        return $this->tags;
    }

    #[ORM\param(Tag)]
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    #[ORM\param(Tag)]
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    #[ORM\param(ArrayCollection | Tag[])]
    public function setTags(ArrayCollection|Tag[] $tags): static
    {
        $this->tags = $tags;

        return $this;
    }


}