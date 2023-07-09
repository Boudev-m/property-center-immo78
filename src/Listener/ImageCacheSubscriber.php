<?php
// Cache Cleaner, Cache Management for images when update or delete property
// Note : with Vich bundle, the image in disk is removed automatically after update/delete property, not the image in cache

namespace App\Listener;

use App\Entity\Property;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    private $cacheManager;

    private $helper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $helper)
    {
        $this->cacheManager = $cacheManager;
        $this->helper = $helper;
    }

    public function getSubscribedEvents()
    {
        // Listens when update or remove entity
        return [
            'preUpdate',
            'preRemove'
        ];
    }

    // Delete image in cache When update a property
    // The Cache
    public function preUpdate(PreUpdateEventArgs $args)
    {
        // get the entity
        $entity = $args->getObject();

        // if uploaded entity is not a Property
        if (!$entity instanceof Property) {
            return;
        }

        // if file uploaded
        // Issue : CacheManager doesn't work with pre update, I use unlink (bad practice)
        if ($entity->getImageFile() instanceof UploadedFile) {

            // Doesn't work
            // $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));

            // Unlink method
            if (key_exists('imageName', $args->getEntityChangeSet())) {
                // get old image name
                $oldImageName = $args->getEntityChangeSet()['imageName'][0];

                // get path of image in cache
                $thumbImageCachePath = 'media' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'property_thumb' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $oldImageName;
                $mediumImageCachePath = 'media' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'property_medium' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $oldImageName;

                // delete the image in cache
                unlink($thumbImageCachePath);
                unlink($mediumImageCachePath);
            }
        }
    }

    // When delete a property entity
    public function preRemove(PreRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Property) {
            return;
        }
        // delete image in cache
        $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
    }
}
