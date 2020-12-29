<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\EvaluationLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ReviewType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('topicalityInterestAndUsefulness', EntityType::class, [
			'class' => EvaluationLevel::class,
			'choice_label' => 'id',
			'label' => 'Aktuálnost, zajímavost a přínosnost',
			'help' => 'Hodnotí se jako ve škole od 1 do 5. Přičemž jednička je nejlepší a pětka nejhorší.',
			'placeholder' => 'Zvolte hodnocení',
			'required' => true,
		])
			->add('originality', EntityType::class, [
				'class' => EvaluationLevel::class,
				'choice_label' => 'id',
				'label' => 'Originalita',
				'help' => 'Hodnotí se jako ve škole od 1 do 5. Přičemž jednička je nejlepší a pětka nejhorší.',
				'placeholder' => 'Zvolte hodnocení',
				'required' => true,
			])
			->add('proffesionalLevel', EntityType::class, [
				'class' => EvaluationLevel::class,
				'choice_label' => 'id',
				'label' => 'Profesionální úroveň',
				'help' => 'Hodnotí se jako ve škole od 1 do 5. Přičemž jednička je nejlepší a pětka nejhorší.',
				'placeholder' => 'Zvolte hodnocení',
				'required' => true,
			])
			->add('languageAndStylisticLevel', EntityType::class, [
				'class' => EvaluationLevel::class,
				'choice_label' => 'id',
				'label' => 'Jazyková a stylistická úroveň',
				'help' => 'Hodnotí se jako ve škole od 1 do 5. Přičemž jednička je nejlepší a pětka nejhorší.',
				'placeholder' => 'Zvolte hodnocení',
				'required' => true,
			])
			->add('comment', TextType::class, [
				'label' => 'Komentář',
				'required' => false,
				'attr' => [
					'class' => 'tinymce',
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit recenzi',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uloží recenzi',
				],
			]);
	}

}
