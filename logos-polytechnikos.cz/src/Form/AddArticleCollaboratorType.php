<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\ArticleCollaborator;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddArticleCollaboratorType extends AbstractType
{

	/** @var ArticleRepository */
	private $articleRepository;

	/**
	 * AddArticleCollaboratorType constructor.
	 */
	public function __construct(ArticleRepository $articleRepository)
	{
		$this->articleRepository = $articleRepository;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->setMethod('POST')
			->add('collaborators', CollectionType::class, [
				'entry_type' => CollaboratorType::class,
				'entry_options' => [
					'data_class' => ArticleCollaborator::class,
				],
				'label' => false,
				'by_reference' => false,
				'allow_add' => true,
				'allow_delete' => true,
				'prototype' => true,
				'required' => true,
				'data' => $this->articleRepository->getCollaborators($options['data']),
				'attr' => [
					'class' => 'collection',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'ukol' => null,
		]);
	}

}
