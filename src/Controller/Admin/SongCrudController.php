<?php

namespace App\Controller\Admin;

use App\DataObjects\SearchObject;
use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;

class SongCrudController extends AbstractCrudController
{

    private Action $searchAction;

    public function __construct(
        private readonly SpotifyWebAPI $spotifyWebAPI,
        private readonly CacheInterface $cache
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Song::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('artists'),
            AssociationField::new('album'),
            TextEditorField::new('lyrics.content')->onlyOnForms()->setLabel('Lyrics'),
            TextEditorField::new('lyrics.meaning')->onlyOnForms()->setLabel('Lyrics meaning'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $this->searchAction = Action::new('search', 'Import from spotify', 'fas fas-search')
            ->linkToCrudAction('searchOnSpotify')
            ->createAsGlobalAction();
        return $actions
            ->add(Crud::PAGE_INDEX, $this->searchAction)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function searchOnSpotify(AdminContext $context, Request $request): Response
    {
        $hasSelection = false;
        $choices = null;
        $searchObject = new SearchObject();
        $form = $this->createFormBuilder($searchObject)
            ->add('songName', TextType::class)
            ->add('artistName', TextType::class)
            ->add('search', SubmitType::class)
            ->add('selectedSong', TextType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $items = $this->getItems($data->getSongName(), $data->getArtistName());
            $hasSelection = true;
            $choices = $this->getChoicesFromItems($items);
        }
        return $this->render('spotify_song_list.html.twig', [
                'form' => $form,
                'action' => $this->searchAction,
                'hasSelection' => $hasSelection,
                'items' => $choices
            ]
        );
    }

    private function getItems(string $name, string $artist)
    {
        $key = str_replace(' ', '_', strtolower($name) . '_' . strtolower($artist));
        return $this->cache->get($key, function () use ($name, $artist) {
            $result = $this->spotifyWebAPI->search(
                $artist . ' ' . $name,
                ['track'],
                [
                    'limit' => 5,
                    'offset' => 0,
                    'market' => 'RO'
                ]
            );
            return $result->tracks->items;
        });
    }


    private function getChoicesFromItems(array $items): array
    {
        $choices = ['Choose one' => null];
        foreach ($items as $key => $item) {
            $text = $item->name . ' by ' . $item->artists[0]->name . ' from album ' . $item->album->name . ' released on ' . $item->album->release_date;
            $choices[$text] = $key;
        }
        return $choices;
    }
}
