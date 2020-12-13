<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\ReviewerStatementState;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReviewerResultType extends AbstractType
{

	/** @var UrlGeneratorInterface */
	private $urlGenerator;

	public function __construct(UrlGeneratorInterface $urlGenerator)
	{
		$this->urlGenerator = $urlGenerator;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->setAction($this->urlGenerator->generate('app_review_submitreview', ['review' => $options['data']->getId()]))
			->add('reviewerStatement', EntityType::class, [
				'class' => ReviewerStatementState::class,
				'choice_label' => 'statement',
				'label' => '',
				'help' => 'Zvolit celkové rozhodnutí a odevzdat recenzi redaktorovi, po té již nebude možná úprava.',
				'placeholder' => 'Zvolte rozhodnutí',
				'required' => true,
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit rozhodnuti',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uložit a předat redaktorovi',
				],
			]);
	}

}
