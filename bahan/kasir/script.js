// Mengambil elemen HTML yang diperlukan
const namaMakanan = document.getElementById('namaMakanan');
const hargaMakanan = document.getElementById('hargaMakanan');
const namaMinuman = document.getElementById('namaMinuman');
const hargaMinuman = document.getElementById('hargaMinuman');
const jumlahMakanan = document.getElementById('jumlahMakanan');
const jumlahMinuman = document.getElementById('jumlahMinuman');
const totalHarga = document.getElementById('totalHarga');
const bayar = document.getElementById('bayar');
const kembalian = document.getElementById('kembalian');
const ppn = document.getElementById("ppn");

// Harga makanan dan minuman
const hargaMakananList = {
  'croffle': 15000,
  'kentangGoreng': 12000,
  'pisangGoreng': 14000,
  'rotiBakar': 13000,
  'onionRing': 15000
};

const hargaMinumanList = {
  'iceBlendCookiesCream': 20000,
  'arabicaCoffee': 18000,
  'chocolateMilkshake': 27000,
  'cappucinoCoffee': 25000,
  'airMineral': 5000
};

// Menampilkan harga makanan saat dipilih
namaMakanan.addEventListener('change', function() {
  const harga = hargaMakananList[this.value];
  if (harga) {
    hargaMakanan.innerHTML = '<h5>Rp. ' + harga + '</h5>';
  } else {
    hargaMakanan.innerHTML = '<h5>Rp. 0</h5>';
  }
});

// Menampilkan harga minuman saat dipilih
namaMinuman.addEventListener('change', function() {
  const harga = hargaMinumanList[this.value];
  if (harga) {
    hargaMinuman.innerHTML = '<h5>Rp. ' + harga + '</h5>';
  } else {
    hargaMinuman.innerHTML = '<h5>Rp. 0</h5>';
  }
  
});

// Menghitung total harga
function hitungTotalHarga() {
  const hargaMakananValue = hargaMakanan.innerText.replace('Rp. ', '');
  const hargaMinumanValue = hargaMinuman.innerText.replace('Rp. ', '');
  const jumlahMakananValue = parseInt(jumlahMakanan.value);
  const jumlahMinumanValue = parseInt(jumlahMinuman.value);
  const total = (hargaMakananValue * jumlahMakananValue) + (hargaMinumanValue * jumlahMinumanValue);
  const totalPPN = total * 0.11;
  
  totalHarga.innerHTML = 'Total Harga : Rp. ' + total;
  ppn.innerHTML = 'PPN (11%) : Rp. ' + totalPPN; 
  
  hitungKembalian(total);
}

// Menghitung kembalian
function hitungKembalian(total) {
  const bayarValue = parseInt(bayar.value);
  const kembalianValue = bayarValue - total;
  
  if (isNaN(bayarValue)) {
    kembalian.innerHTML = '<h5> Rp. 0</h5>';
    return;
  }

  kembalian.innerHTML = '<h5> Rp. ' + kembalianValue + '</h5>';
}

// Ketika tombol submit ditekan
document.querySelector('form').addEventListener('submit', function(event) {
  event.preventDefault();
  hitungTotalHarga();
});