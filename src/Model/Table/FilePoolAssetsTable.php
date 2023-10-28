<?php
declare(strict_types=1);


namespace FilePool\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->uuid('owner_id')
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
}
