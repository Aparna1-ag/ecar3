<?php
// check role
$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['name'];

include 'addOrders.php';
include 'addCustomer.php';
include 'placePurchase.php';

?>

<style>

  
.navlink {
        text-decoration: none;
        padding: 0.8rem;
        font-size: 24px;
        font-weight: 700;
      }

     .navlink:focus {
        
        text-decoration: none;
      }

      .navlink:hover {
        
        text-decoration: none;
        
      } 
 

      .nav-tabs {
        height: 3.5rem;
      }

      .ongoing {
        color: #14881c;
      }

      .procurement {
        color: blue;

      }

      .completed {
        color: #8c00ff;

      }
      


      .sold {
        color: #ff0077;

      }

      .sales {
       
        color: #ff0077;
        
      }

      .control {
        color: #3b4907;

      }


      .complaints {
        color: #ff6a00;
      }





      .procurement-active   {
        border-bottom: 5px solid blue;

}

.ongoing-active {
        
        border-bottom: 5px solid #14881c;
      }


.completed-active {
        border-bottom: 5px solid  #8c00ff;

} 

.sold-active {
  border-bottom: 5px solid #ff0077;

}

.sales-active {
  border-bottom: 5px solid #ff0077;
  

}

.control-active {
  border-bottom: 5px solid #3b4907;
  
}

.complaints-active {
  border-bottom: 5px solid #ff6a00;
  
}

a {
  text-decoration: none;
  color: black;
}

.list-numbers {
  width:1.8rem; 
  height:1.8rem; 
  margin-bottom: 0.8rem;
  border-radius:50%;
}

/* 


.ongoing {
        color: #1d144f;

      }

      .completed {
        color: #1d144f;

      }
      


      .sold {
        color: #1d144f;

      }

      .sales {
        color: #1d144f;
        
      }

      .control {
        color: #1d144f;

      }


      .complaints {
        color: #1d144f;
      }





      .ongoing-active   {
        border-bottom: 5px solid #1d144f;

}


.completed-active {
        border-bottom: 5px solid  #1d144f;

} 

.sold-active {
  border-bottom: 5px solid #1d144f;

}

.sales-active {
  border-bottom: 5px solid #1d144f;

}

.control-active {
  border-bottom: 5px solid #1d144f;
  
}

.complaints-active {
  border-bottom: 5px solid #1d144f;
  
}


 */















li a.active {
  border-bottom: 5px solid inherit;

}
    



      /* .activeprojects:hover {
        color: green;

      }

    

      .projectstatus:hover {
        color: orange;
        
      } 

      .notassigned:hover {
        color: purple;

      } */




 

</style>





<?php
// Get the current URL path
$current_page = basename($_SERVER['REQUEST_URI']);

// Define the target page
$procurement_page = 'liveOrders.php';
$ongoing_page = 'production.php';
$completed_orders_page = 'completedOrders.php';
$sold_page = 'soldOrders.php';
$sales_page = 'sale.php';
$manage_page = 'controlaccess.php';

 

 if ($current_page === $procurement_page):
  echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('.procurement').classList.add('procurement-active')
   }
)
  
  </script>";
endif; 

if ($current_page === $ongoing_page):
  echo "<script>
  
  document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('.ongoing').classList.add('ongoing-active')
   }
  )
  
  </script>";
   endif;

if ($current_page === $completed_orders_page):
  echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('.completed').classList.add('completed-active')
   }
)
  
  </script>";
endif; 

if ($current_page === $sold_page):
  echo "<script>
  
  document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('.sold').classList.add('sold-active')
   }
  )
  
  </script>";
   endif;


   if ($current_page === $sales_page):
    echo "<script>
    
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.sales').classList.add('sales-active')
     }
    )
    
    </script>";
     endif;


     if ($current_page === $manage_page):
      echo "<script>
      
      document.addEventListener('DOMContentLoaded', () => {
      document.querySelector('.control').classList.add('control-active')
       }
      )
      
      </script>";
       endif;









?>


 







<nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
  <div class="container-fluid d-flex justify-content-between" style="width: 92%">
    <div>
 <a class="navbar-brand" href="Dashboard.php"> <img src="https://eurofloat.com.au/cdn/shop/files/logo_new_160x.png?v=1633607473" alt="logo"></a>
</div>
   

<div class="d-flex" id="navbarSupportedContent">

  

<?php


if ($user_role !== 'vendor') { 

$current_page = basename($_SERVER['REQUEST_URI']);


$dashboard_page = 'Dashboard.php';
$control_access_page = 'controlaccess.php';
$customer_support_page = 'customer_complaint.php';
$sales_page = 'sale.php';




if ($current_page !== $sales_page ): ?>
  <a href="sale.php" class="mx-4 d-flex justify-content-center">
    <div class="pt-2 ">
    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/investment-report-8759794-7190835.png
"  style="width: 4rem; height:3.5rem;" />

<div class="d-flex justify-content-center" > <p> Sales  </p></div>
  
  </div>


</a>

  <?php endif; 



if ($current_page !== $control_access_page ): ?>
  <a href="controlaccess.php" class="mx-4 d-flex justify-content-center">
    <div class=" ">
    <img src="https://static.vecteezy.com/system/resources/previews/028/605/133/original/gear-3d-rendering-icon-illustration-comma-separated-no-special-characters-free-png.png"  style="width: 4rem; height:auto" />

<div class="d-flex justify-content-center" > <p> Manage  </p></div>
  
  </div>


</a>

  <?php endif; 
  if ($current_page !== $customer_support_page): ?>
 <a href="customer_complaint.php" class=" d-flex justify-content-center ">
    <div>
   <div class="d-flex justify-content-center mx-4 ">  <img src="https://static.vecteezy.com/system/resources/previews/010/872/716/original/3d-customer-service-icon-png.png"  style="width: 4rem; height:auto" /> </div>

<div class=" " > <p class="me-4 ms-2"> Customer support  </p></div>
  
  </div>


</a>  <?php endif; ?>
  


<?php  if ($current_page !== $dashboard_page): ?>
 <div class="m-auto">  <a href="Dashboard.php" class="btn btn-lg bg-gradient bg-secondary px-5 ms-3 me-5  fs-4 text-light " >Go to Dashboard</a> </div>
<?php endif; ?>



<?php } 

if ($user_role === 'vendor') { 

  $vendors_dashboard = 'VendorsDashboard.php';
  $vendors_past_records = 'vendorrecord.php';


?>



<?php  if ($current_page !== $vendors_dashboard): ?>
 <div class="m-auto">  <a href="VendorsDashboard.php" class="btn btn-lg bg-gradient bg-secondary px-5 ms-3 me-5  fs-4 text-light " >Go to Dashboard</a> </div>
<?php endif; ?>

<?php  if ($current_page !== $vendors_past_records): ?>
 <div class="m-auto">  <a href="vendorrecord.php" class="btn btn-lg bg-gradient px-5 ms-3 me-5  fs-4 text-light "  style="background: #722424;" >See Past Records</a> </div>
<?php endif; ?>


<?php  } ?>





    
                       
                           
   <div class="m-auto">   <a class="btn btn-danger text-light border-0 mt-2" style="height: 2.5rem;" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutModal"> Logout </a> </div>
                       
                    
    
      

   
    </div>
  </div>
</nav>


<div class="my-4 container-fluid w-100"  >

<div class="col-12 m-auto">
<ul id="myTab" role="tablist" class="nav nav-tabs flex-column flex-sm-row text-center "  style="height:4rem;" >

<?php if ($user_role !== 'vendor') { ?>
<li class="nav-item flex-sm-fill navitem">
  <img src="https://centralcoastorthodontics.com.au/wp-content/uploads/2020/06/photo_2020-06-30_12-10-48.png" class="list-numbers" />
          <a id="Table-tab" data-toggle="tab" href="./liveOrders.php" role="tab" aria-controls="Table" aria-selected="true" class="navlink text-uppercase font-weight-bold active procurement">Procurement</a>
        </li>
        <p class="text-secondary"> ------------------- </p>
        <li class="nav-item flex-sm-fill navitem ">
        <img src="https://i.pinimg.com/originals/63/ed/36/63ed36adbfee7f7428e5d8df0333fccb.png" class="list-numbers" />

          <a id="Table-tab" data-toggle="tab" href="./production.php" role="tab" aria-controls="Table" aria-selected="true" class="navlink text-uppercase font-weight-bold active ongoing">Production</a>
        </li>
        <p class="text-secondary"> ------------------- </p>
        <li class="nav-item flex-sm-fill navitem">
        <img src="https://prodcms.go.com.mt/wp-content/uploads/2023/04/circle-3-purple.png" class="list-numbers" style="width:2.1rem; height:2.1rem;" />

          <a id="not-assigned-tab" data-toggle="tab" href="completedOrders.php" role="tab" aria-controls="not-assigned" aria-selected="false" class=" navlink text-uppercase font-weight-bold completed">Completed Productions</a>
        </li>
        <p class="text-secondary"> ------------------- </p>
        <li class="nav-item flex-sm-fill navitem">
        <img src="https://kingsuniversitycollege.edu.my/wp-content/uploads/2021/09/4.png" class="list-numbers" style="width:2.4rem; height:2.4rem;" />

          <a id="active-projects-tab" data-toggle="tab" href="soldOrders.php" role="tab" aria-controls="Graphical" aria-selected="false" class=" navlink text-uppercase font-weight-bold sold">Sold Orders</a>
        </li>

        <?php }   ?>

     

      </ul>
     
</div>



</div>


