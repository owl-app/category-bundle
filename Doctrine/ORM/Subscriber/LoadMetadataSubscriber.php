<?php

declare(strict_types=1);

namespace Owl\Bundle\CategoryBundle\Doctrine\ORM\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

final class LoadMetadataSubscriber implements EventSubscriber
{
    /** @var array */
    private $subjects;

    public function __construct(array $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @return string[]
     *
     * @psalm-return list{'loadClassMetadata'}
     */
    public function getSubscribedEvents(): array
    {
        return [
            'loadClassMetadata',
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArguments): void
    {
        $metadata = $eventArguments->getClassMetadata();

        $metadataFactory = $eventArguments->getEntityManager()->getMetadataFactory();

        foreach ($this->subjects as $subject => $class) {
            // if ($class['category']['classes']['model'] === $metadata->getName()) {
            //     $categorizableEntity = $class['subject'];
            //     $categorizableEntityMetadata = $metadataFactory->getMetadataFor($categorizableEntity);

            //     $metadata->mapManyToOne($this->createSubjectMapping($categorizableEntity, $subject, $categorizableEntityMetadata));
            // }

            if ($class['subject'] === $metadata->getName()) {
                $categoryEntity = $class['category']['classes']['model'];
                $categoryEntityMetadata = $metadataFactory->getMetadataFor($categoryEntity);

                $metadata->mapManyToOne($this->createCategoriesMapping($categoryEntity, $categoryEntityMetadata));
            }
        }
    }

    // private function createSubjectMapping(
    //     string $categorizableEntity,
    //     string $subject,
    //     ClassMetadata $categorizableEntityMetadata
    // ): array {
    //     return [
    //         'fieldName' => 'categorySubject',
    //         'targetEntity' => $categorizableEntity,
    //         'inversedBy' => 'categories',
    //         'joinColumns' => [[
    //             'name' => $subject . '_id',
    //             'referencedColumnName' => $categorizableEntityMetadata->fieldMappings['id']['columnName'] ?? $categorizableEntityMetadata->fieldMappings['id']['fieldName'],
    //             'nullable' => false,
    //             'onDelete' => 'CASCADE',
    //         ]],
    //     ];
    // }

    /**
     * @return (((string|true)[]|string)[]|string)[]
     *
     * @psalm-return array{fieldName: 'category', targetEntity: string, joinColumns: list{array{name: 'category_id', referencedColumnName: string, nullable: true, onDelete: 'SET NULL'}}, orderBy: array{createdAt: 'DESC'}}
     */
    private function createCategoriesMapping(
        string $categoryEntity,
        ClassMetadata $categoryEntityMetadata,
    ): array {
        return [
            'fieldName' => 'category',
            'targetEntity' => $categoryEntity,
            'joinColumns' => [[
                'name' => 'category_id',
                'referencedColumnName' => $categoryEntityMetadata->fieldMappings['id']['columnName'] ?? $categoryEntityMetadata->fieldMappings['id']['fieldName'],
                'nullable' => true,
                'onDelete' => 'SET NULL',
            ]],
            'orderBy' => ['createdAt' => 'DESC'],
        ];
    }
}
