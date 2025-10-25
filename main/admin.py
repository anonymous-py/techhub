from django.contrib import admin
from .models import Category, Product, Cart, CartItem, Order, OrderItem

# ================= CATEGORY =================
@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    list_display = ('name', 'description')
    search_fields = ('name',)


# ================= PRODUCT =================
@admin.register(Product)
class ProductAdmin(admin.ModelAdmin):
    list_display = ('name', 'category', 'price', 'stock', 'available', 'added_time')
    list_filter = ('category', 'added_time')
    search_fields = ('name', 'description')
    readonly_fields = ('added_time',)
    list_editable = ('price', 'stock')
    ordering = ('-added_time',)
    fieldsets = (
        (None, {
            'fields': ('name', 'category', 'description', 'price', 'stock', 'pic')
        }),
        ('Advanced options', {
            'classes': ('collapse',),
            'fields': ('added_time',),
        }),
    )


# ================= CART ITEM INLINE =================
class CartItemInline(admin.TabularInline):
    model = CartItem
    extra = 0
    readonly_fields = ('total_price',)
    autocomplete_fields = ('product',)


# ================= CART =================
@admin.register(Cart)
class CartAdmin(admin.ModelAdmin):
    list_display = ('customer', 'created_at', 'total_price')
    inlines = [CartItemInline]
    readonly_fields = ('created_at',)
    search_fields = ('customer__email',)
    ordering = ('-created_at',)


# ================= ORDER ITEM INLINE =================
class OrderItemInline(admin.TabularInline):
    model = OrderItem
    extra = 0
    readonly_fields = ('total_price',)
    autocomplete_fields = ('product',)


# ================= ORDER =================
@admin.register(Order)
class OrderAdmin(admin.ModelAdmin):
    list_display = ('id', 'customer', 'status', 'total_price', 'created_at', 'updated_at')
    list_filter = ('status', 'created_at', 'updated_at')
    search_fields = ('customer__email',)
    readonly_fields = ('total_price', 'created_at', 'updated_at')
    inlines = [OrderItemInline]
    actions = ['mark_as_shipped', 'mark_as_delivered']

    def mark_as_shipped(self, request, queryset):
        queryset.update(status='shipped')
    mark_as_shipped.short_description = "Mark selected orders as Shipped"

    def mark_as_delivered(self, request, queryset):
        queryset.update(status='delivered')
    mark_as_delivered.short_description = "Mark selected orders as Delivered"


# ================= ORDER ITEM =================
@admin.register(OrderItem)
class OrderItemAdmin(admin.ModelAdmin):
    list_display = ('order', 'product', 'quantity', 'price', 'total_price')
    readonly_fields = ('total_price',)
    search_fields = ('product__name', 'order__customer__email')
    list_filter = ('order',)
