<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ChangeTemplateType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('articleTemplate', FileType::class, [
				'label' => 'Šablona',
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new File([
						'maxSize' => '10M',
						'mimeTypes' => [
//							'application/msword',
//							'application/vnd.ms-word',
//							'application/x-msword',
							'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
							'application/msword; charset=binary',
						],
						'mimeTypesMessage' => 'Vkládejte soubor v požadovaném formátu: DOCX!',
					]),
				],
				'help' => 'Podporovány jsou soubory: docx. Maximální velikost souboru je 10MB.',
				'help_html' => true,
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit šablonu',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Upravit informace',
				],
			]);
	}

}
