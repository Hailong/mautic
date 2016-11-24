<?php

namespace MauticPlugin\MauticShareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Share.
 *
 * @ORM\Table(name="page_shares")
 * @ORM\Entity(repositoryClass="ShareRepository")
 */
class Share
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=255)
     */
    private $target;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="shared_date", type="datetime")
     */
    private $sharedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Mautic\LeadBundle\Entity\Lead")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="text")
     */
    private $imageUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="read_count", type="integer", options={"default":0})
     */
    private $readCount;

    /**
     * @ORM\ManyToOne(targetEntity="Mautic\LeadBundle\Entity\LeadDevice")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $device;

    /**
     * @ORM\ManyToOne(targetEntity="Share")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set source.
     *
     * @param string $source
     *
     * @return Share
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set target.
     *
     * @param string $target
     *
     * @return Share
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set sharedDate.
     *
     * @param \DateTime $sharedDate
     *
     * @return Share
     */
    public function setSharedDate($sharedDate)
    {
        $this->sharedDate = $sharedDate;

        return $this;
    }

    /**
     * Get sharedDate.
     *
     * @return \DateTime
     */
    public function getSharedDate()
    {
        return $this->sharedDate;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Share
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lead.
     *
     * @param string $lead
     *
     * @return Share
     */
    public function setLead($lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead.
     *
     * @return string
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Share
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Share
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Share
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set imageUrl.
     *
     * @param string $imageUrl
     *
     * @return Share
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set readCount.
     *
     * @param int $readCount
     *
     * @return Share
     */
    public function setReadCount($readCount)
    {
        $this->readCount = $readCount;

        return $this;
    }

    /**
     * Get readCount.
     *
     * @return int
     */
    public function getReadCount()
    {
        return $this->readCount;
    }

    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    public function getDevice()
    {
        return $this->device;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
