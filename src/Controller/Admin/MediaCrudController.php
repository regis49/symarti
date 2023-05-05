<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
//use EasyCorp\Bundle\EasyAdminBundle\Field\VichFileField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');


        $videoField = Field::new('imageFile','Media')
            ->setFormType(VichFileType::class)
            ->setFormTypeOptions([
                'allow_file_upload' => true,
                'allow_delete' => true,
                'download_uri' => false,
                'download_label' => false,
                'delete_label' => false,
                'asset_helper' => true,
                'attr' => ['accept' => 'video/*'],
                'label' => 'Vidéo',
                'required' => false,
                'required' => Crud::PAGE_EDIT !== $pageName,
                'help' => 'Taille maximale autorisée: 500 Mo',
            ])
            ->addJsFiles('/bundles/easyadmin/upload-video.js')
            ->setCustomOption('vich_uploader_mapping', 'formation_videos')
            ->setCustomOption('vich_uploader_allow_delete', true)
            ->setCustomOption('vich_uploader_property_name', 'filename')
            ->setCustomOption('vich_uploader_help', 'Taille maximale autorisée: 500 Mo')
            ->setCustomOption('vich_uploader_label', 'Vidéo')
            ->setCustomOption('vich_uploader_max_size', '500M');


        /* $videoField =Field::new('filename')
            ->setFormType(VichFileType::class);
          /*  ->setLabel('Video')
            ->setFormTypeOptions([
                'required' => Crud::PAGE_EDIT !== $pageName,
            ])
            ->setDownloadLink(false)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]')
            ->setBasePath('/uploads/videos'); */


        yield $videoField;
    }
}
