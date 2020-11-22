<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddArticleType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
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
			->add('attachment', FileType::class, [
				'label' => 'Článek: Podporovány jsou soubory: pdf,docx',
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
							'application/msword;
					    charset=binary',
						],
						'mimeTypesMessage' => 'Vkládejte soubor v požadovaném formátu: PDF, DOC, DOCX!',
					]),
				],
				'help' => 'Vkládejte soubor v požadovaném formátu: PDF, DOC, DOCX. Maximální velikost souboru je 10MB.',
				'help_html' => true,
			])
			->add('degreeBefore', TextType::class, [
				'label' => 'Titul před',
				'mapped' => false,
				'attr' => [
					'maxlength' => 35,
				],
				'help' => 'Zadejte tituly uváděné před jménem. Maximální délka je 35 znaků.',
				'required' => false,
			])
			->add('email', EmailType::class, [
				'mapped' => false,
				'required' => false,
				'help' => 'Zadejte e-mail spoluautora.',
			])
			->add('nameCollaborator', TextType::class, [
				'label' => 'Spoluautor',
				'mapped' => false,
				'attr' => [
					'maxlength' => 255,
				],
				'help' => 'Zadejte všechna jména příjmení. Maximální délka je 255 znaků.',
				'required' => false,
			])
			->add('degreeAfter', TextType::class, [
				'label' => 'Titul za',
				'mapped' => false,
				'attr' => [
					'maxlength' => 30,
				],
				'help' => 'Zadejte tituly uváděné za jménem. Maximální délka je 30 znaků.',
				'required' => false,
			])
			->add('addCollaborator', SubmitType::class, [
				'label' => 'Přidat',
				'attr' => [
					'class' => 'btn btn-primary btn-sm',
					'title' => 'Uloží článek a přidá spouautora',
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit článek',
				'attr' => [
					'class' => 'btn btn-primary btn-sm',
					'title' => 'Uloží článek',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([]);
	}

}
