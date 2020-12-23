<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\Magazine;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleTypeArticle extends ArticleCollaboratorType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('name', TextType::class, [
				'label' => 'Název článku',
				'required' => true,
				'attr' => [
					'maxlength' => 255,
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Zadejte název článku',
					]),
				],
				'help' => 'Zadejte název článku, maximální dálka je omezena na 255 znaků.',
			])
			->add('magazine', EntityType::class, [
				'class' => Magazine::class,
				'label' => 'Zařazení článku k edici',
				'required' => true,
				'choice_label' => static function (Magazine $magazine) {
					return $magazine->getDeadline()->format('Y') . '/Ročník' . ($magazine->getDeadline()->format('Y') - 2009) . '/Číslo' . $magazine->getNumber() . ' - ' . $magazine->getMagazineThema()->getTheme();
				},
				'query_builder' => static function (EntityRepository $er) {
					return $er->createQueryBuilder('m')
						->where('m.currentState = 2 and m.deadline >= :now')
						->setParameter('now', new DateTimeImmutable());
				},
			])
			->add('attachment', FileType::class, [
				'label' => 'Článek',
				'mapped' => false,
				'required' => false,
				'constraints' => [
					new File([
						'maxSize' => '10M',
						'mimeTypes' => [
							'application/pdf',
							'application/x-pdf',
							'application/msword',
							'application/vnd.ms-word',
							'application/x-msword',
							'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
							'application/msword; charset=binary',
						],
						'mimeTypesMessage' => 'Vkládejte soubor v požadovaném formátu: PDF, DOC, DOCX!',
					]),
				],
				'help' => 'Podporovány jsou soubory: pdf, docx. Maximální velikost souboru je 10MB.',
				'help_html' => true,
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit článek',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uloží článek',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'buttonAddCollaboratorDisabled' => true,
		]);
	}

}
