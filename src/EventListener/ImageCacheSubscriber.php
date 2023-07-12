<?php
// Purpose : Cache Cleaner, Cache Management for images when update or delete property
// Note : with Vich bundle, the image in disk is removed automatically after update/delete property, not the image in cache

namespace App\EventListener;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriberInterface
{
    private $cacheManager;

    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents(): array
    {
        // Listens when update or remove entity
        return [
            Events::preUpdate,
            Events::preRemove
        ];
    }

    // Delete image in cache When update a property
    public function preUpdate(PreUpdateEventArgs $args)
    {
        // get the entity
        $entity = $args->getObject();
        // if uploaded entity is a Picture
        // dump('updated');
        if (!$entity instanceof Picture) {
            return;
            // if file is uploaded
            // if ($entity->getImageFile() instanceof UploadedFile) {
            // delete image in cache
            // }
        }
        dd('end');
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));

        // // if file uploaded
        // // Issue : CacheManager doesn't work with pre update, I use unlink (bad practice)
        // if ($entity->getImageFile() instanceof UploadedFile) {

        //     // Doesn't work
        //     // $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));

        //     // Unlink method
        //     if (key_exists('imageName', $args->getEntityChangeSet())) {
        //         // get old image name
        //         $oldImageName = $args->getEntityChangeSet()['imageName'][0];

        //         // get path of image in cache
        //         $thumbImageCachePath = 'media' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'property_thumb' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $oldImageName;
        //         $mediumImageCachePath = 'media' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'property_medium' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $oldImageName;

        //         // delete the image in cache
        //         unlink($thumbImageCachePath);
        //         unlink($mediumImageCachePath);
        //     }
        // }
    }

    // When delete a property entity
    public function preRemove(PreRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Picture) { // Picture is uploadable (Vich\Uploadable)
            // delete image in cache
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
    }
}
