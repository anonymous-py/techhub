from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .models import Product, Category, Cart, CartItem, Order, OrderItem

def product_list(request):
    categories = Category.objects.all()
    query = request.GET.get('q')
    category_id = request.GET.get('category')

    products = Product.objects.all()

    if category_id:
        products = products.filter(category__id=category_id)
    if query:
        products = products.filter(name__icontains=query)
        messages.info(request, f"Search results for '{query}'")

    context = {
        'products': products,
        'categories': categories,
    }
    return render(request, 'main/product_list.html', context)



def product_detail(request, pk):
    product = get_object_or_404(Product, pk=pk)
    return render(request, 'main/product_detail.html', {'product': product})


@login_required
def add_to_cart(request, product_id):
    product = get_object_or_404(Product, id=product_id)
    cart, created = Cart.objects.get_or_create(customer=request.user)
    cart_item, created = CartItem.objects.get_or_create(cart=cart, product=product)
    if not created:
        cart_item.quantity += 1
    cart_item.save()
    messages.success(request, f"Added {product.name} to your cart.")
    return redirect('cart_detail')


@login_required
def cart_detail(request):
    cart, created = Cart.objects.get_or_create(customer=request.user)
    items = cart.cartitem_set.all()
    total = cart.total_price()
    return render(request, 'main/cart_detail.html', {'cart': cart, 'items': items, 'total': total})


@login_required
def remove_from_cart(request, item_id):
    item = get_object_or_404(CartItem, id=item_id, cart__customer=request.user)
    item.delete()
    messages.info(request, "Item removed from cart.")
    return redirect('cart_detail')


@login_required
def place_order(request):
    cart = get_object_or_404(Cart, customer=request.user)
    if not cart.cartitem_set.exists():
        messages.warning(request, "Your cart is empty.")
        return redirect('product_list')

    order = Order.objects.create(customer=request.user)
    for item in cart.cartitem_set.all():
        OrderItem.objects.create(
            order=order,
            product=item.product,
            quantity=item.quantity,
            price=item.product.price
        )
        item.product.stock -= item.quantity
        item.product.save()
    order.calculate_total()
    cart.delete()

    messages.success(request, "Order placed successfully!")
    return render(request, 'main/order_success.html', {'order': order})


def guest_add_to_cart(request, product_id):
    messages.warning(request, "Please log in to add items to your cart.")
    return redirect('login')


from django.shortcuts import render

def about_view(request):
    return render(request, 'main/about.html')

def contact_view(request):
    return render(request, 'main/contact.html')


@login_required
def cart_view(request):
    cart, created = Cart.objects.get_or_create(customer=request.user)
    items = cart.cartitem_set.all()
    return render(request, 'main/cart.html', {'cart': cart, 'items': items})