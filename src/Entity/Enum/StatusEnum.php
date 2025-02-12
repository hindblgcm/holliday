<?php

namespace App\Entity\Enum;

  enum StatusEnum : string
  {
      case draft = 'draft';
      case submitted = 'submitted';
      case approved = 'approved';
      case rejected = 'rejected';
  }


  ?>