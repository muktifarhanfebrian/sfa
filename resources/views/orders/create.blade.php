@extends('layouts.app')

@section('title', 'Buat Order Baru')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 fw-bold">1. Pilih Pelanggan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pelanggan</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Pilih Toko --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div id="hiddenItemsArea"></div>

                    <hr>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg" id="btnSubmit" disabled>
                            Simpan Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">2. Pilih Produk</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <select id="productSelect" class="form-select">
                            <option value="" data-price="0">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}">
                                    {{ $product->name }} (Stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" id="qtyInput" class="form-control" placeholder="Jml" min="1" value="1">
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="button" class="btn btn-secondary" onclick="addItem()">
                            <i class="bi bi-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Keranjang Belanja</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th width="15%">Qty</th>
                            <th width="25%" class="text-end">Subtotal</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody">
                        </tbody>
                    <tfoot class="fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Total Bayar:</td>
                            <td class="text-end" id="grandTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div id="emptyCartMessage" class="text-center py-4 text-muted">
                    Keranjang masih kosong.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = []; // Array untuk menyimpan data sementara

    function addItem() {
        const productSelect = document.getElementById('productSelect');
        const qtyInput = document.getElementById('qtyInput');

        const productId = productSelect.value;
        const productName = productSelect.options[productSelect.selectedIndex].getAttribute('data-name');
        const productPrice = parseFloat(productSelect.options[productSelect.selectedIndex].getAttribute('data-price'));
        const productStock = parseInt(productSelect.options[productSelect.selectedIndex].getAttribute('data-stock'));
        const qty = parseInt(qtyInput.value);

        // Validasi
        if (!productId) {
            alert("Pilih produk dulu!");
            return;
        }
        if (qty <= 0) {
            alert("Jumlah minimal 1");
            return;
        }
        if (qty > productStock) {
            alert("Stok tidak cukup! Sisa: " + productStock);
            return;
        }

        // Cek apakah produk sudah ada di cart?
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.qty += qty; // Update qty
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                qty: qty
            });
        }

        renderCart(); // Update tampilan tabel
        productSelect.value = ""; // Reset pilihan
        qtyInput.value = 1;
    }

    function removeItem(index) {
        cart.splice(index, 1); // Hapus dari array
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const hiddenArea = document.getElementById('hiddenItemsArea');
        const grandTotalElem = document.getElementById('grandTotal');
        const emptyMsg = document.getElementById('emptyCartMessage');
        const btnSubmit = document.getElementById('btnSubmit');

        tbody.innerHTML = "";
        hiddenArea.innerHTML = "";
        let total = 0;

        // Loop array cart untuk buat baris tabel
        cart.forEach((item, index) => {
            const subtotal = item.price * item.qty;
            total += subtotal;

            // 1. Tampilan Tabel
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td class="text-end">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;

            // 2. Hidden Input untuk dikirim ke Laravel
            // Format name: items[0][product_id], items[0][quantity]
            hiddenArea.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
            `;
        });

        // Update Total & UI
        grandTotalElem.innerText = "Rp " + total.toLocaleString('id-ID');

        if (cart.length > 0) {
            emptyMsg.style.display = 'none';
            btnSubmit.disabled = false; // Aktifkan tombol simpan
        } else {
            emptyMsg.style.display = 'block';
            btnSubmit.disabled = true;
        }
    }
</script>
@endsection
