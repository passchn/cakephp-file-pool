<?php

declare(strict_types=1);

namespace FilePool\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Text;
use FilePool\Model\Entity\FilePoolAsset;
use Nette\Utils\Strings;
use Psr\Http\Message\UploadedFileInterface;

/**
 * FilePoolAssets Controller
 *
 * @property \App\Model\Table\FilePoolAssetsTable $FilePoolAssets
 * @method \FilePool\Model\Entity\FilePoolAsset[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilePoolAssetsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        if ($this->components()->has('FormProtection')) {
            $this->FormProtection->setConfig('unlockedActions', [
                'index', 'add', 'view', 'edit', 'delete',
            ]);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->getRequest()->allowMethod(['post']);

        $filePoolAssets = $this->FilePoolAssets->find()
            ->where([
                'FilePoolAssets.owner_id' => $this->getRequest()->getData('ownerId'),
                'FilePoolAssets.owner_source' => $this->getRequest()->getData('ownerModel'),
            ])
            ->contain([
                'Assets',
            ])
            ->orderAsc('FilePoolAssets.sort');

        return $this->getResponse()
            ->withStringBody((string)json_encode($filePoolAssets));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->getRequest()->allowMethod(['post']);

        $filePoolAsset = $this->FilePoolAssets->newEmptyEntity();

        $file = $this->getRequest()->getData('file');
        if (!$file instanceof UploadedFileInterface) {
            throw new InternalErrorException();
        }

        $title = preg_replace(
            "/!\s+!/",
            ' ',
            preg_replace(
                "/[^\da-zäöüß]/i",
                ' ',
                Text::slug(
                    ucwords(
                        Strings::before(mb_strtolower(
                            $file->getClientFilename()
                        ), '.', -1),
                        "\t\r\n\f\v -_+.",
                    )
                )
            )
        );

        $filePoolAsset = $this->FilePoolAssets->patchEntity($filePoolAsset, [
            'owner_source' => $this->getRequest()->getData('ownerModel'),
            'owner_id' => $this->getRequest()->getData('ownerId'),
            'asset' => [
                'filename' => $file,
                'title' => $title,
            ],
        ]);
        $this->FilePoolAssets->saveOrFail($filePoolAsset);

        return $this->getResponse()
            ->withStringBody((string)json_encode($filePoolAsset));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function edit()
    {
        $this->getRequest()->allowMethod(['post']);
        $request = $this->getRequest();

        /**
         * @var FilePoolAsset $filePoolAsset
         */
        $filePoolAsset = $this->FilePoolAssets->find()
            ->where([
                'FilePoolAssets.id' => $request->getData('fileId'),
                'FilePoolAssets.owner_source' => $request->getData('ownerModel'),
                'FilePoolAssets.owner_id' => $request->getData('ownerId'),
            ])
            ->contain(['Assets'])
            ->firstOrFail();

        $filePoolAsset = $this->FilePoolAssets->patchEntity($filePoolAsset, [
            'asset' => [
                'title' => $request->getData('title', $filePoolAsset->asset->title),
                'category' => $request->getData('category', $filePoolAsset->asset->category),
                'description' => $request->getData('description', $filePoolAsset->asset->description),
            ],
            'sort' => $request->getData('sort', $filePoolAsset->sort),
        ]);

        $this->FilePoolAssets->saveOrFail($filePoolAsset, [
            'updateSort' => $request->getData('sort') !== null,
        ]);

        return $this->getResponse()
            ->withStringBody((string)json_encode($filePoolAsset));
    }

    /**
     * Delete method
     *
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'delete']);
        $filePoolAsset = $this->FilePoolAssets->get($this->getRequest()->getData('id'));
        $this->FilePoolAssets->deleteOrFail($filePoolAsset);

        return $this->getResponse()->withStringBody('OK');
    }
}
