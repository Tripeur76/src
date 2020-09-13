<script type="text/javascript">
      var url = "http://localhost:8000/shop/cart";        

      // Requete ajax
      var xhr = new XMLHttpRequest();
      xhr.open('POST', url,true);
      xhr.onload = function() {

          if (xhr.status === 200) {

              // Formatage des données JSON
              var datas = xhr.responseText;
              datas = JSON.parse(datas);
              datas = datas.message;

              console.log('contenu du panier : ');
              console.log(datas);
          }
          else{alert('Request failed.  Error : ' + xhr.status);}
      };
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("add="+productID + "-" + productQuantity);	// envoi (POST)
</script>
