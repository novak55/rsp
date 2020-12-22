<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\MagazineThema;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MagazineType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('magazineThema', EntityType::class, [
				'class' => MagazineThema::class,
				'choice_label' => 'theme',
				'required' => true,
				'label' => 'Téma',
			])
			->add('deadLine', DateType::class, [
				'input' => 'datetime_immutable',
				'required' => true,
				'label' => 'Uzávěrka',
				'widget' => 'single_text',
				'constraints' => [
					new NotBlank([
						'message' => 'Vyplňte název číselníku stavu.',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit stav',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uložit stav',
				],
			]);
	}

}
