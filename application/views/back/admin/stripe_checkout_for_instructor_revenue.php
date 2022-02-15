<!DOCTYPE html>

<html lang="en">

<head>

    <title>Stripe | <?php echo get_settings('system_name'); ?></title>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="<?php echo base_url('assets/payment/css/stripe.css'); ?>" rel="stylesheet">

    <link name="favicon" type="image/x-icon" href="<?php echo base_url(); ?>uploads/system/favicon.png" rel="shortcut icon" />

</head>

<body>


    <!--required for getting the stripe token-->



    <img src="<?php echo base_url() . 'uploads/system/logo-light.png'; ?>" width="15%;" style="opacity: 0.05;">

    <form method="post" action="<?php echo site_url('admin/stripe_payment/' . $payout_id); ?>">

        <label>

            <div id="card-element" class="field is-empty"></div>

            <span><span><?php echo translate('credit_or_debit_card'); ?></span></span>

        </label>

        <button type="submit">

            <?php echo translate('pay'); ?> <?php echo $amount_to_pay . ' ' . get_settings('stripe_currency'); ?>

        </button>

        <div class="outcome">

            <div class="error" role="alert"></div>

            <div class="success">

                Success! Your Stripe token is <span class="token"></span>

            </div>

        </div>

        <div class="package-details">

            <strong><?php echo translate('instructor'); ?> | <?php echo $instructor_name; ?></strong> <br>

            <strong><?php echo translate('payout_status'); ?> | <?php echo translate('pending'); ?></strong> <br>

            <strong><?php echo translate('payment_due'); ?> | <?php echo $amount_to_pay; ?></strong> <br>

        </div>

        <input type="hidden" name="stripeToken" value="">

    </form>

    <img src="https://stripe.com/img/about/logos/logos/blue.png" width="25%;" style="opacity: 0.05;">

    <script src="https://js.stripe.com/v3/"></script>

    <script>
           var stripe_key = '<?php echo $public_live_key; ?>';
        var stripe = Stripe(stripe_key);
        var elements = stripe.elements();

        var card = elements.create("card", {
            hidePostalCode: true,
            iconStyle: "solid",
            style: {
                base: {
                    iconColor: "#8898AA",
                    color: "white",
                    lineHeight: "36px",
                    fontWeight: 300,
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSize: "19px",

                    "::placeholder": {
                        color: "#8898AA"
                    }
                },
                invalid: {
                    iconColor: "#e85746",
                    color: "#e85746"
                }
            },
            classes: {
                focus: "is-focused",
                empty: "is-empty"
            }
        });
        card.mount("#card-element");

        var inputs = document.querySelectorAll("input.field");
        Array.prototype.forEach.call(inputs, function(input) {
            input.addEventListener("focus", function() {
                input.classList.add("is-focused");
            });
            input.addEventListener("blur", function() {
                input.classList.remove("is-focused");
            });
            input.addEventListener("keyup", function() {
                if (input.value.length === 0) {
                    input.classList.add("is-empty");
                } else {
                    input.classList.remove("is-empty");
                }
            });
        });

        var form = document.querySelector("form");

        function setOutcome(result) {
            var successElement = document.querySelector(".success");
            var errorElement = document.querySelector(".error");
            successElement.classList.remove("visible");
            errorElement.classList.remove("visible");

            if (result.token) {
                // Use the token to create a charge or a customer
                // https://stripe.com/docs/charges
                //successElement.querySelector(".token").textContent = result.token.id;
                form.querySelector("input[name=stripeToken]").value = result.token.id;
                form.submit();
                //successElement.classList.add("visible");
            } else if (result.error) {
                errorElement.textContent = result.error.message;
                errorElement.classList.add("visible");
            }
        }

        card.on("change", function(event) {
            setOutcome(event);
        });

        document.querySelector("form").addEventListener("submit", function(e) {
            e.preventDefault();
            var extraDetails = {
                //name: form.querySelector("input[name=cardholder-name]").value
            };
            stripe.createToken(card, extraDetails).then(setOutcome);
        });
    </script>

    <script type="text/javascript">
        get_stripe_currency('<?php echo get_settings('stripe_currency'); ?>');
    </script>

</body>

</html>