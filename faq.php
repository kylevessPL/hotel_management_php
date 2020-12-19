<?php
include 'helpers/include_all.php';

$sql = "SELECT name FROM payment_forms";
$result = query($sql);

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body class="min-vh-100 d-flex flex-column">
<?php view('navbar.php'); ?>
<div class="container-fluid main-container flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>Frequently asked questions</p>
            <div class="row mb-4">
                <div class="col-12 col-xl-7 mb-lg-0">
                    <div class="accordion" id="accordionFaq">
                        <div class="card">
                            <div class="card-header pl-2" id="headingOne">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqOne"><i class="fa fa-plus mr-2"></i> Do you have any meal packages?</button>
                                </h2>
                            </div>
                            <div id="faqOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>Yes, we have breakfast, lunch & dinner packs - each of them available in affordable price. More information <a href="/dashboard/additional-services" target="_blank">here</a>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingTwo">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqTwo"><i class="fa fa-plus mr-2"></i> Are guest visitors allowed in the hotel?</button>
                                </h2>
                            </div>
                            <div id="faqTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>As a HoteLA we value our customers choice as well as the security which is our main concern. That is why we provide special guest room when you can spend time with your guests. However direct in-room visits are not allowed</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingThree">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqThree"><i class="fa fa-plus mr-2"></i> Do you provide any discounts for children?</button>
                                </h2>
                            </div>
                            <div id="faqThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>Yes, of course. For children under 13 years old you can order an additional bed. No additional costs. More information <a href="/dashboard/additional-services" target="_blank">here</a>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingFour">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqFour"><i class="fa fa-plus mr-2"></i> Is Internet access available at the hotel?</button>
                                </h2>
                            </div>
                            <div id="faqFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>Yes, there is a Free Wi-Fi Hotspot available throughout the building. If you are intenersted, feel free ask for a password in the reception.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingFive">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqFive"><i class="fa fa-plus mr-2"></i> What time are the additional meals served?</button>
                                </h2>
                            </div>
                            <div id="faqFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>Breakfast is served to the room at 9 a.m.<br>Lunch & Dinner Packs give you the ability to try everything from our buffet menu. Buffet is opened 10 a.m. - 9 p.m. every day.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingSix">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqSix"><i class="fa fa-plus mr-2"></i> Is there a parking available?</button>
                                </h2>
                            </div>
                            <div id="faqSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <p>We offer a huge parking lot on hotel premises. The parking lot is guarded 24h/7 a week to ensure your vehicle will stay safe the whole time you stay there.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header pl-2" id="headingSeven">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link text-decoration-none collapsed" data-toggle="collapse" data-target="#faqSeven"><i class="fa fa-plus mr-2"></i> What are the available payment forms?</button>
                                </h2>
                            </div>
                            <div id="faqSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionFaq">
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li>Currently payment forms listed below are supported:
                                            <ul>
                                                <?php while($row = mysqli_fetch_array($result)) { echo "
                                                <li>$row[0]</li>
                                                "; } ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php view('chat.php'); ?>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="/assets/js/faq.js"></script>

</body>
</html>