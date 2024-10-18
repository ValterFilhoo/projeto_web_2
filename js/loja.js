document.addEventListener("DOMContentLoaded", function () {
  const removeProductButtons = document.getElementsByClassName("remover");
  for (var i = 0; i < removeProductButtons.length; i++) {
    removeProductButtons[i].addEventListener("click", function () {
    (event.target).parentElement.parentElement.remove();
    });
  }
});
function updatetotal (){
let total = 0
document.addEventListener("DOMContentLoaded", function() {
  const cartProducts = document.getElementsByClassName("cart-product");
  for (var i = 0; i < cartProducts.length; i++) {
    const produtopreco = cartProducts[i].getElementsByClassName("produto-preco")[0].innerText.replace("R$","").replace(",", ".")
    const produtoquantidade = cartProducts[i].getElementsByClassName("input-preco")[0].value
    console.log(produtopreco)
    total += produtopreco * produtoquantidade
    console.log(total)
  }
    document.getElementsByClassName("valor-total").innerText = "R$" = total

});
}