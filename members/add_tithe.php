<div class="row-fluid">
  <!-- block -->
  <div class="block">
    <div class="navbar navbar-inner block-he9ader">
      <div class="muted pull-left"><i class="icon-plus-sign icon-large"> Enter Your Giving</i></div>
    </div>
    <div class="block-content collapse in">
      <div class="span12">
        <form method="post" id="payment-form">
          <div class="control-group">
            <div class="controls">
              <input class="input focused" name="amount" id="amount" type="text" placeholder="Amount" required>
            </div>
          </div>

          <div class="control-group">
            <div class="controls">
              <input class="input focused" name="trcode" id="trcode" type="text" placeholder="Transaction Code" required>
            </div>
          </div>

          <!-- Stripe Payment Elements for Visa -->
          <div class="control-group">
            <div class="controls">
              <div id="card-element" class="form-control">
                <!-- Stripe Card Element will be inserted here -->
              </div>
              <div id="card-errors" role="alert" style="color: red;"></div>
            </div>
          </div>

          <div class="control-group">
            <div class="controls">
              <button name="save" class="btn btn-info" id="submit-button" data-placement="right" title="Click to Save">
                <i class="icon-plus-sign icon-large"> Pay with Visa</i>
              </button>
              <script type="text/javascript">
                $(document).ready(function() {
                  $('#submit-button').tooltip('show');
                  $('#submit-button').tooltip('hide');
                });
              </script>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /block -->
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
  // Create a Stripe client
  var stripe = Stripe('YOUR_PUBLISHABLE_KEY'); // Replace with your actual publishable key
  
  // Create an instance of Elements
  var elements = stripe.elements();
  
  // Custom styling can be passed to options when creating an Element
  var style = {
    base: {
      color: '#32325d',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a'
    }
  };
  
  // Create an instance of the card Element
  var card = elements.create('card', {style: style});
  
  // Add an instance of the card Element into the `card-element` <div>
  card.mount('#card-element');
  
  // Handle real-time validation errors from the card Element
  card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });
  
  // Handle form submission
  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Disable the submit button to prevent repeated clicks
    document.getElementById('submit-button').disabled = true;
    
    stripe.createToken(card).then(function(result) {
      if (result.error) {
        // Inform the user if there was an error
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
        
        // Enable the submit button
        document.getElementById('submit-button').disabled = false;
      } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
      }
    });
  });
  
  // Submit the form with the token ID
  function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    
    // Add amount and transaction code as hidden fields
    var amountInput = document.createElement('input');
    amountInput.setAttribute('type', 'hidden');
    amountInput.setAttribute('name', 'amount');
    amountInput.setAttribute('value', document.getElementById('amount').value);
    form.appendChild(amountInput);
    
    var trcodeInput = document.createElement('input');
    trcodeInput.setAttribute('type', 'hidden');
    trcodeInput.setAttribute('name', 'trcode');
    trcodeInput.setAttribute('value', document.getElementById('trcode').value);
    form.appendChild(trcodeInput);
    
    // Submit the form
    form.submit();
  }
</script>

<?php
if (isset($_POST['save']) || isset($_POST['stripeToken'])) {
  $amount = $_POST['amount'];
  $trcode = $_POST['trcode'];
  
  // Process Stripe payment
  if (isset($_POST['stripeToken'])) {
    require_once('vendor/autoload.php'); // Include Stripe PHP library
    
    \Stripe\Stripe::setApiKey('YOUR_SECRET_KEY'); // Replace with your actual secret key
    
    try {
      $charge = \Stripe\Charge::create([
        'amount' => $amount * 100, // Amount in cents
        'currency' => 'usd',
        'source' => $_POST['stripeToken'],
        'description' => 'Donation payment',
      ]);
      
      // Only save to database if payment was successful
      mysqli_query($conn, "INSERT INTO tithe (Amount, Trcode, na) VALUES('$amount','$trcode','$session_id')") or die(mysqli_error());
      
      ?>
      <script>
        window.location = "Tithes.php";
        $.jGrowl("Payment Successful and Giving Successfully added", {
          header: 'Payment Complete'
        });
      </script>
      <?php
    } catch (\Stripe\Exception\CardException $e) {
      // Handle card errors
      ?>
      <script>
        $.jGrowl("Payment Failed: <?php echo $e->getError()->message; ?>", {
          header: 'Payment Error'
        });
      </script>
      <?php
    }
  } else {
    // Old non-payment method
    mysqli_query($conn, "INSERT INTO tithe (Amount, Trcode, na) VALUES('$amount','$trcode','$session_id')") or die(mysqli_error());
    ?>
    <script>
      window.location = "Tithes.php";
      $.jGrowl("The Giving Successfully added", {
        header: 'Giving added'
      });
    </script>
    <?php
  }
}
?>