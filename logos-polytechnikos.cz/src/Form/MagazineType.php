<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\MagazineState;
use App\Entity\MagazineThema;
use App\Repository\MagazineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class MagazineType extends AbstractType
{

	/** @var MagazineRepository */
	private $magazineRepository;

	public function __construct(MagazineRepository $magazineRepository)
	{
		$this->magazineRepository = $magazineRepository;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$lastDate = null;
		if ($options['data']->getDeadline() === null) {
			$lastDate = $this->magazineRepository->getLastDateMagazine();
		}
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
				'attr' => [
					'min' => $lastDate,
				],
			])
			->add('number', ChoiceType::class, [
				'placeholder' => 'Doplnit automaticky',
				'label' => 'Číslo edice',
				'required' => false,
				'choices' => [1 => 1, 2, 3, 4, 5],
			])
			->add('currentState', EntityType::class, [
				'class' => MagazineState::class,
				'choice_label' => 'state',
				'required' => true,
				'label' => 'Stav přístupnosti edice',
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
