<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

  <div style="width: 50%; margin: auto; padding-top: 200px">
    <form id="form-submit" action="{{ route('plan.default-payment') }}" method="POST">
      @csrf
      <input type="hidden" name="payment_method" id="payment_method">
    </form>
    <div>
      <input id="card-holder-name" type="text">
 
      <!-- Stripe Elements Placeholder -->
      <div id="card-element"></div>
      
      <button id="card-button">
          Process Payment
      </button>
    </div>
  </div>
  <script src="https://js.stripe.com/v3/"></script>
 
  <script>
      const stripe = Stripe('pk_test_51O03djCGT6SiitjYT4GNlyweG3yCajhmO0rKO7Gh4c3GKrPDslzUDTvUDjG7m1JiE2eWdsNLsKE3Xm0mUpH7iC9800b02ckfJQ');
  
      const elements = stripe.elements();
      const cardElement = elements.create('card');
  
      cardElement.mount('#card-element');

      const cardHolderName = document.getElementById('card-holder-name');
      const cardButton = document.getElementById('card-button');
      
      cardButton.addEventListener('click', async (e) => {
          const { paymentMethod, error } = await stripe.createPaymentMethod(
              'card', cardElement, {
                  billing_details: { name: cardHolderName.value }
              }
          );
      
          if (error) {
              // Display "error.message" to the user...
          } else {
            const inpMethod = document.getElementById('payment_method');
            inpMethod.setAttribute('value',paymentMethod.id);

            const form = document.getElementById('form-submit');
            form.submit();
          }
      });
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>