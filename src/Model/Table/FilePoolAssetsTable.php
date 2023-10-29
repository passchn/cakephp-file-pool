<?php
declare(strict_types=1);


namespace FilePool\Model\Table;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use FilePool\Model\Entity\FilePoolAsset;

/**
 * FilePoolAssets Model
 *
 * @method \FilePool\Model\Entity\FilePoolAsset newEmptyEntity()
 * @method \FilePool\Model\Entity\FilePoolAsset newEntity(array $data, array $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[] newEntities(array $data, array $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset get($primaryKey, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \FilePool\Model\Entity\FilePoolAsset[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Assets\Model\Table\AssetsTable&\Cake\ORM\Association\BelongsTo $Assets
 */
class FilePoolAssetsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('file_pool_assets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        foreach (Configure::read('FilePool.FilePoolAssetsTable.Behaviors', []) as $behavior) {
            $this->addBehavior($behavior);
        }

        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id',
            'className' => 'Assets.Assets'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('owner_source')
            ->maxLength('owner_source', 255)
            ->requirePresence('owner_source', 'create')
            ->notEmptyString('owner_source');

        $validator
            ->scalar('owner_id')
            ->maxLength('owner_id', 255)
            ->requirePresence('owner_id', 'create')
            ->notEmptyString('owner_id');

        $validator
            ->uuid('asset_id')
            ->notEmptyString('asset_id');

        $validator
            ->nonNegativeInteger('sort')
            ->notEmptyString('sort');

        return $validator;
    }

    public function beforeFind(EventInterface $e, SelectQuery $query): SelectQuery
    {
        return $query->orderByAsc('sort');
    }

    public function beforeSave(EventInterface $event, FilePoolAsset $filePoolAsset, \ArrayObject $options): bool
    {
        $updateSortForExisting = $options->offsetExists('updateSort') && $options->offsetGet('updateSort') === true;
        $this->updateSort($filePoolAsset, $updateSortForExisting);

        return true;
    }

    /**
     * The method will set a sort index for new Entities by default.
     *
     * For existing Entities and their related Entities to be updated,
     * the $updateSortForExisting flag must be set to true.
     */
    protected function updateSort(FilePoolAsset $filePoolAsset, bool $updateSortForExisting = false)
    {
        $conditions = [
            'owner_source' => $filePoolAsset->owner_source,
            'owner_id' => $filePoolAsset->owner_id,
        ];

        $getHighestSort = function (array $conditions): ?int {
            /**
             * @var FilePoolAsset|null $highestSorted
             */
            $highestSorted = $this->find()->where($conditions)
                ->orderByDesc('sort')
                ->first();
            if ($highestSorted === null) {
                return null;
            }
            return $highestSorted->sort;
        };

        if ($filePoolAsset->isNew()) {
            $highestSort = $getHighestSort($conditions);
            $filePoolAsset->sort = $highestSort !== null ? $highestSort + 1 : 1;
            return;
        }
        if (!$updateSortForExisting) {
            return;
        }

        $otherFilePoolAssets = $this->find()
            ->where($conditions)
            ->where(['id !=' => $filePoolAsset->id])
            ->orderByAsc('sort');

        $highestSort = $getHighestSort($conditions);
        $targetSort = $filePoolAsset->sort;
        if ($targetSort <= 0) {
            $targetSort = 1;
        }
        if ($highestSort !== null && $highestSort < $targetSort) {
            $targetSort = $highestSort;
        }
        $count = $otherFilePoolAssets->count();
        if ($count < $targetSort) {
            $targetSort = $count + 1;
        }

        $sort = 1;

        /**
         * @var FilePoolAsset $otherFilePoolAsset
         */
        foreach ($otherFilePoolAssets as $otherFilePoolAsset) {
            if ($sort === $targetSort) {
                $sort++;
            }
            $otherFilePoolAsset->sort = $sort;
            $sort++;
        }

        $this->saveManyOrFail($otherFilePoolAssets);
        $filePoolAsset->sort = $targetSort;
    }
}
