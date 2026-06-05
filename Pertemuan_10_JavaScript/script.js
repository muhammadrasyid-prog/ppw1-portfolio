// Contoh penulisan JS dengan external file
// Contoh untuk output dengan InnerHTML
const judul = document.getElementById("judul");
const konten = document.getElementById("konten");

judul.innerHTML = "Belajar Javascript untuk Pemula";
konten.innerHTML =
  "Javascript adalah bahasa pemrograman yang digunakan untuk membuat halaman web menjadi interaktif. Dengan Javascript, kita dapat membuat berbagai macam efek dan fitur pada halaman web, seperti animasi, validasi form, dan masih banyak lagi.";

// Form pendataan mahasiswa dengan opertaor JS
function tambahData() {
  const isMahasiswa = confirm("Apakah Anda mahasiswa PPW1?");

  if (isMahasiswa) {
    // Lakukan sesuatu jika pengguna mengonfirmasi
    const nama = prompt("Masukkan nama Anda:");
    const nim = prompt("Masukkan NIM Anda");
    const angkatan = prompt("Angkatan berapa Anda?");

    // Tampilkan data yang dimasukkan
    const dataMahasiswa = `Nama: ${nama}\nNIM: ${nim}\nAngkatan: ${angkatan}`;
    alert(`Data Mahasiswa:\n${dataMahasiswa}`);

    // hitung berapa tahun lagi lulus(asumsikan 4 tahun dari angkatan)
    const tahunLulus = parseInt(angkatan) + 4;
    const tahunSekarang = new Date().getFullYear();
    const tahunTersisa = tahunLulus - tahunSekarang;

    alert(
      `Anda akan lulus pada tahun ${tahunLulus}. Tersisa ${tahunTersisa} tahun lagi.`,
    );

    // Cek apakah NIM genap atau ganjil
    if (nim % 2 === 0) {
      alert("NIM Anda adalah genap.");
    } else {
      alert("NIM Anda adalah ganjil.");
    }

    // masukkan data input ke dalam tabel
    // 2. Validasi: Pastikan user tidak menekan 'Cancel' di salah satu prompt
    if (nama && nim && angkatan) {
      // Tampilkan data yang dimasukkan
      const dataMahasiswa = `Nama: ${nama}\nNIM: ${nim}\nAngkatan: ${angkatan}`;
      alert(`Data Mahasiswa:\n${dataMahasiswa}`);

      const tabel = document.getElementById("dataMahasiswa");

      // 3. Masukkan ke tabel dengan susunan yang sesuai
      tabel.innerHTML += `
            <tr>
                <td>${nama}</td>
                <td>${nim}</td>
                <td>${angkatan}</td>
                <td>${tahunLulus}</td>
                <td>${nim % 2 === 0 ? "Genap" : "Ganjil"}</td>
            </tr>
        `;
    } else {
      alert("Semua data harus diisi!");
    }
  } else {
    // Lakukan sesuatu jika pengguna membatalkan
    alert("Anda bukan mahasiswa PPW1.");
  }
}
