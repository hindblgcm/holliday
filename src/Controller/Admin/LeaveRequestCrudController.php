<?php

namespace App\Controller\Admin;

use App\Entity\LeaveRequest;
use App\Entity\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LeaveRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LeaveRequest::class;
    }


    public function configureFields(string $pageName): iterable
    {

        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('user');
        yield DateField::new('startDate');
        yield DateField::new('endDate');
        yield TextField::new('reason');
        yield ChoiceField::new('status')->setChoices([
            'draft' => StatusEnum::draft,
            'Submitted' => StatusEnum::submitted,
            'Approved' => StatusEnum::approved,
            'Rejected' => StatusEnum::rejected,
        ]);
    }
}
