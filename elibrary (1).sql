-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Bulan Mei 2025 pada 07.19
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elibrary`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aktivitas`
--

CREATE TABLE `aktivitas` (
  `id_aktivitas` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aktivitas`
--

INSERT INTO `aktivitas` (`id_aktivitas`, `id_user`, `aktivitas`, `waktu`) VALUES
(1, 2, 'Menambahkan buku berjudul: Ensiklopedia Hewan', '2025-05-18 19:27:35'),
(2, 2, 'Menghapus buku dengan ID: 2', '2025-05-19 07:28:08'),
(3, 2, 'Menambahkan buku berjudul: Hujan', '2025-05-19 07:29:40'),
(4, 2, 'Mengedit buku: Hujan', '2025-05-19 08:31:53'),
(5, 2, 'Mengedit buku: Hujan', '2025-05-19 08:34:39'),
(6, 2, 'Menambahkan buku berjudul: Cinta Bersemi Di Bumi Seoul', '2025-05-19 10:17:20'),
(7, 2, 'Mengedit buku: Cinta Bersemi Di Bumi Seoul', '2025-05-19 10:18:49'),
(8, 2, 'Mengedit buku: Cinta Bersemi Di Bumi Seoul', '2025-05-19 10:47:05'),
(9, 2, 'Mengedit buku: Cinta Bersemi Di Bumi Seoul', '2025-05-19 11:14:47'),
(10, 2, 'Menambahkan buku berjudul: Cantik Itu Luka', '2025-05-19 11:20:56'),
(11, 2, 'Mengedit buku: Cantik Itu Luka', '2025-05-19 11:41:43'),
(12, 2, 'Mengedit buku: Cantik Itu Luka', '2025-05-19 11:44:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookmark`
--

CREATE TABLE `bookmark` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bookmark`
--

INSERT INTO `bookmark` (`id`, `id_user`, `id_book`, `created_at`) VALUES
(4, 1, 4, '2025-05-19 08:36:41'),
(6, 1, 5, '2025-05-19 10:43:41'),
(7, 3, 6, '2025-05-19 11:39:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id_book` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `isi_buku` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id_book`, `judul`, `penulis`, `tahun_terbit`, `id_kategori`, `cover`, `deskripsi`, `isi_buku`) VALUES
(3, 'Ensiklopedia Hewan', 'Farida Nur Kumala', '2019', 16, 'https://down-id.img.susercontent.com/file/6f34e72e30f3b0a0285895101bb374c7', 'Buku ini merupakan Ensiklopedai yang berisikan tentang hewan hewan\r\nyang ada di Indonesia yang terbagi menjadi 2 bagian besar yang ditinjau dari\r\ntulang belakang hewan. Yakni hewan yang bertulang belakang (vertebrata)\r\ndan hewan yang tidak bertulang belakang (Avertebrata).', '### Bab 1 VERTEBRATA\r\nVertebrata merupakan hewan yang memiliki\r\ntulang belakang dengan memiliki beberpa ciri:\r\n1. Bentuk tubuh simetris bilateral,\r\n2. Memiliki rangka dalam (endoskeleton),\r\n3 Memiliki ruas-ruas tulang belakang,\r\n4. Letak susunan saraf membentang di atas saluran pencernaan,\r\n5. Memiliki organ tubuh,\r\n6. Alat pencernaan sudah sempurna,\r\n7. Reproduksi terjadi secara generatif\r\n8. memiliki sistem peredaran tertutup.\r\n\r\n### Bab 2 AVERTEBRATA\r\nAvertebrata adalah hewan hewan yang tidak memiliki tulang belakang. Hewan\r\nAvertebrata memiliki beberapa ciri khusus diantaranya:\r\na. Tidak memiliki tulang belakang\r\nb. Rangka tubuh terletak diluar tubuh\r\nc. Susunan saraf terletak dibawah pencernaan\r\nd. Otak tidak dilindungi tulang tengkorak\r\ne. Selnya multiseluler\r\nf. Kebanykan alat kelamin jantan dan betina berada dalam satu tubuh,\r\nnamun ada juga yang terpisah'),
(4, 'Hujan', 'Tere Liye', '2020', 10, 'https://inc.mizanstore.com/aassets/img/com_cart/produk/rbak-030--.JPG', 'Sinopsis Hujan karya Tere Liye bercerita tentang Lail, seorang gadis yang menjadi yatim piatu setelah bencana gempa dan letusan gunung berapi merenggut nyawa orang tuanya di usia 13 tahun. Ia bertemu dengan Esok, seorang pemuda yang menjadi penyemangat hidupnya di tengah bencana dan membantu Lail bertahan hidup.', '### Bab 1 VERTEBRATA\r\n\r\nVertebrata merupakan hewan yang memiliki\r\n\r\ntulang belakang dengan memiliki beberpa ciri:\r\n\r\n1. Bentuk tubuh simetris bilateral,\r\n\r\n2. Memiliki rangka dalam (endoskeleton),\r\n\r\n3 Memiliki ruas-ruas tulang belakang,\r\n\r\n4. Letak susunan saraf membentang di atas saluran pencernaan,\r\n\r\n5. Memiliki organ tubuh,\r\n\r\n6. Alat pencernaan sudah sempurna,\r\n\r\n7. Reproduksi terjadi secara generatif\r\n\r\n8. memiliki sistem peredaran tertutup.\r\n\r\n\r\n\r\n### Bab 2 AVERTEBRATA\r\n\r\nAvertebrata adalah hewan hewan yang tidak memiliki tulang belakang. Hewan\r\n\r\nAvertebrata memiliki beberapa ciri khusus diantaranya:\r\n\r\na. Tidak memiliki tulang belakang\r\n\r\nb. Rangka tubuh terletak diluar tubuh\r\n\r\nc. Susunan saraf terletak dibawah pencernaan\r\n\r\nd. Otak tidak dilindungi tulang tengkorak\r\n\r\ne. Selnya multiseluler\r\n\r\nf. Kebanykan alat kelamin jantan dan betina berada dalam satu tubuh,\r\n\r\nnamun ada juga yang terpisah	Hapus'),
(5, 'Cinta Bersemi Di Bumi Seoul', 'Miss Leonie', '2011', 1, 'https://i.pinimg.com/736x/ba/41/06/ba4106c25da88fb9e994d9c55f0f95f5.jpg', 'Novel ini menceritakan kisah cinta antara Han Soon-Hee dan Jung Tae-Woo selama musim panas di Seoul. Awalnya Soon-Hee diminta berpura-pura menjadi kekasih rahasia Tae-Woo untuk menghilangkan gosip buruk tentangnya, namun mereka benar-benar jatuh cinta. Hubungan mereka terancam ketika identitas Soon-Hee terbongkar dan kecelakaan yang menimpanya.', '###Bab 1Jejak Awal di Negeri Ginseng\r\nPesawat yang mengantarkan Hana mendarat dengan mulus di Bandara Internasional Incheon, Seoul. Dari balik jendela, ia menatap hamparan kota metropolitan yang tak pernah tidur itu, gedung-gedung tinggi yang menjulang, dan jalanan yang penuh cahaya. Udara musim semi yang sejuk menyambutnya, menghembuskan aroma berbeda dari tanah airnya.\r\n\r\nHana menarik nafas dalam, merasakan campuran antara antusiasme dan kegelisahan yang berbaur dalam dadanya. Ini adalah awal dari petualangannya yang baru, di negeri yang selama ini hanya bisa ia saksikan lewat drama dan lagu-lagu K-Pop. Ia teringat pesan ibunya sebelum berangkat, “Jangan lupa jaga diri, dan jangan malu bertanya kalau kesulitan. Korea itu indah, tapi bisa juga bikin kamu merasa sendirian.”\r\n\r\nDengan koper beroda yang agak berat, Hana melangkah ke area kedatangan. Seketika ia merasa terombang-ambing oleh keramaian dan kebisingan. Bahasa asing terdengar di mana-mana, tulisan Korea yang rumit di papan pengumuman membuat matanya sedikit kabur. Ia mencoba menenangkan diri dan melihat ke sekeliling mencari petunjuk.\r\n\r\nBerjalan perlahan, Hana mulai mencari tempat untuk menunggu shuttle bus kampus yang akan mengantarnya ke asrama. Namun, semua tanda arah tertulis dalam Hangul, huruf Korea yang belum pernah ia pelajari. Rasa panik mulai muncul saat ia tak bisa menemukan tempat yang benar. Keringat dingin mengalir di dahinya.\r\n\r\n“Excuse me?” suara lembut seorang pria dari belakangnya menyapa dalam bahasa Inggris yang fasih.\r\n\r\nHana menoleh, bertemu dengan mata hangat seorang pemuda Korea dengan senyum ramah. “Kamu butuh bantuan?” tanyanya sambil mengangkat tas ransel.\r\n\r\n“Ah, iya. Aku… aku mencari shuttle bus untuk ke universitas. Tapi aku nggak tahu harus ke mana,” jawab Hana sedikit terbata.\r\n\r\n“Nama aku Joon. Aku mahasiswa di universitas yang sama dengan kamu,” jelas pria itu sambil menunjuk ke arah shuttle bus yang berhenti tidak jauh dari situ.\r\n\r\nHana merasa lega seketika. “Senang bertemu denganmu, Joon. Aku Hana.”\r\n\r\nMereka berjalan bersama, dan Joon mulai menceritakan tentang kehidupan di Seoul. Ia berbicara dengan antusias tentang tempat-tempat keren untuk dikunjungi, makanan favoritnya, dan bagaimana cuaca di musim semi membuat kota ini terlihat seperti dalam drama Korea.\r\n\r\nSepanjang perjalanan ke kampus, Hana merasa hangat dan tenang. Meski masih asing dengan banyak hal, keberadaan Joon membuatnya merasa tidak sendirian. Setiba di kampus, mereka berpisah setelah Joon memastikan Hana sudah tahu cara menuju asramanya.\r\n\r\nSebelum berpisah, Joon berkata, “Kalau kamu butuh apa-apa, jangan ragu hubungi aku. Aku akan senang membantu.”\r\n\r\nHana mengangguk sambil tersenyum, hatinya terasa lebih ringan dari sebelumnya. Ia tahu, perjalanannya di negeri ginseng ini baru saja dimulai, dan mungkin di sini ia akan menemukan sesuatu yang lebih dari sekadar ilmu dan pengalaman.\r\n\r\n###Bab 2 Aroma Kopi dan Senja di Hongdae\r\nHari pertama kuliah sudah berakhir, tapi pikiran Hana belum juga tenang. Ia duduk di sebuah kafe kecil di kawasan Hongdae, salah satu daerah paling hidup di Seoul yang terkenal dengan seni dan musiknya. Kafe itu berbeda dengan kedai kopi biasa yang pernah ia kunjungi di Jakarta. Lantainya yang terbuat dari kayu tua berderit halus setiap kali ada yang lewat, meja dan kursi kayu sederhana, serta dinding penuh dengan lukisan dan foto-foto unik membuat suasana terasa intim dan hangat.\r\n\r\nHana menyesap kopinya perlahan, merasakan rasa pahit yang menyengat tapi menyegarkan. Di luar jendela, matahari mulai menurunkan sinarnya, langit berubah warna menjadi jingga dan ungu. Suara musik akustik dari sebuah pertunjukan kecil di jalanan ikut masuk ke dalam kafe, membungkus suasana dengan kehangatan yang sulit dijelaskan.\r\n\r\nPintu kafe terbuka dan masuklah seorang pemuda dengan jaket hitam dan tas ransel. Hana langsung mengenali dia—Joon, pria yang membantunya di bandara kemarin. Joon tersenyum melihat Hana, lalu duduk di hadapannya tanpa ragu.\r\n\r\n\"Halo, Hana! Aku nggak nyangka kamu juga suka tempat ini,\" sapa Joon sambil memesan kopi hitam.\r\n\r\n\"Halo, Joon! Aku juga suka suasananya. Rasanya beda dan bikin tenang,\" jawab Hana sambil tersenyum malu.\r\n\r\nMereka mulai ngobrol, membahas pengalaman pertama Hana di Seoul, kebingungannya dengan bahasa, dan bagaimana ia merindukan keluarga di Indonesia. Joon mendengarkan dengan serius, sesekali memberikan saran dan cerita lucu tentang perjuangannya belajar bahasa Korea.\r\n\r\n\"Kalau kamu mau, aku bisa ajarin bahasa Korea dasar. Aku juga senang banget ngajarin teman baru,\" kata Joon sambil tersenyum.\r\n\r\nHana merasa hatinya hangat mendengar itu. \"Makasih, Joon. Aku pasti butuh banyak bantuan.\"\r\n\r\nObrolan mereka mengalir bebas, membahas musik favorit, drama Korea, dan impian masa depan. Hana merasakan ada ikatan yang tumbuh, sesuatu yang membuatnya nyaman dan ingin terus dekat dengan Joon.\r\n\r\nSaat matahari mulai terbenam, langit berubah menjadi kanvas jingga keemasan yang indah. Mereka memutuskan keluar kafe dan berjalan menyusuri jalanan Hongdae yang mulai ramai dengan lampu-lampu warna-warni dan pertunjukan jalanan. Suara tawa dan musik menggema, menciptakan suasana hidup yang penuh energi.\r\n\r\nHana berjalan di samping Joon, merasakan kehangatan yang berbeda saat berada dekat dengannya. Jantungnya berdetak lebih cepat, dan ia sadar itu bukan hanya karena udara dingin malam itu. Tapi apakah ini cinta? Ia belum yakin, hanya sebuah rasa manis yang mulai tumbuh di hatinya.\r\n\r\nSebelum berpisah, Joon menatap Hana dengan tatapan serius, “Kalau kamu butuh teman, kapan saja, aku ada. Biar kamu nggak merasa sendirian di sini.”\r\n\r\nHana tersenyum tulus, “Terima kasih, Joon. Aku senang bisa kenal kamu.”\r\n\r\nMalam itu, di kamar kecilnya di asrama, Hana merenung. Kota Seoul yang megah dan sibuk ternyata bisa menyimpan kehangatan lewat pertemanan baru. Mungkin, di bumi Seoul ini, cinta pun bisa bersemi perlahan, membawa harapan baru dalam hidupnya.\r\n\r\n###Bab 3 Hujan dan Rahasia di Taman Namsan\r\nHari berikutnya, Seoul menunjukkan wajahnya yang berbeda. Mendung tebal menyelimuti langit, dan udara mulai terasa dingin dan lembap. Hana membuka jendela kamar asramanya, mengamati tetesan hujan yang turun perlahan, membasahi jalanan dan dedaunan di taman dekat kampus.\r\n\r\nMalam sebelumnya, setelah pertemuan di Hongdae, pikirannya masih dipenuhi oleh kenangan hangat bersama Joon. Tapi hari ini, ada sesuatu yang berbeda. Perasaan ragu mulai menyusup pelan-pelan, menimbulkan pertanyaan-pertanyaan yang tak ia tahu harus dijawab bagaimana.\r\n\r\n“Apakah aku benar-benar bisa menyesuaikan diri di sini?” pikir Hana sambil mengenakan mantel tebal sebelum keluar kamar.\r\n\r\nDengan payung berwarna merah cerah, Hana berjalan menyusuri jalan setapak menuju Taman Namsan, sebuah tempat yang terkenal sebagai spot romantis dengan pemandangan kota Seoul yang menakjubkan. Ia ingin menyendiri sejenak, mencari jawaban atas kegelisahan yang mengganggunya.\r\n\r\nDi tengah jalan setapak yang basah, Hana duduk di bangku kayu yang berderit lembut saat ia menempatinya. Ia memandangi pepohonan yang bergoyang tertiup angin, aroma tanah basah menyatu dengan bau segar daun yang baru terkena hujan.\r\n\r\nTiba-tiba, suara langkah kaki yang tergesa mendekat membuat Hana menoleh. Joon muncul, mengenakan jaket hitam dan membawa payung besar.\r\n\r\n“Hana, kamu di sini?” tanyanya sambil tersenyum. “Aku pikir kamu mungkin butuh teman.”\r\n\r\nHana merasa ada kehangatan yang mengalir ke dalam tubuhnya meski udara dingin menusuk tulang. “Iya, aku butuh keluar dari asrama sebentar. Hujan ini malah bikin aku jadi banyak mikir.”\r\n\r\nMereka berjalan bersama di bawah payung yang sama, berbagi kehangatan kecil saat hujan turun rintik-rintik. Joon bercerita tentang masa kecilnya di Seoul, tentang mimpi-mimpinya yang sederhana tapi berarti, dan tentang perjuangan kerasnya agar bisa kuliah di universitas ternama itu.\r\n\r\n“Kadang aku juga merasa sendirian di sini,” kata Joon sambil menatap ke bawah. “Tapi aku percaya, semua perjuangan ini nggak akan sia-sia.”\r\n\r\nHana menatap wajahnya yang serius. “Aku juga takut, Joon. Takut gagal, takut nggak bisa bertahan jauh dari keluarga.”\r\n\r\nJoon menggenggam tangan Hana sejenak, memberikan kekuatan tanpa kata. “Kamu nggak sendiri, Hana. Aku akan ada di sini, bersamamu.”\r\n\r\nMereka duduk di sebuah gazebo kecil yang teduh, menikmati hujan yang mulai reda. Hana membuka rahasianya, tentang kerinduannya pada rumah, tentang kesulitannya belajar bahasa, dan tentang perasaannya yang mulai tumbuh tapi juga penuh keraguan.\r\n\r\nJoon mendengarkan dengan penuh perhatian, kemudian berkata, “Setiap perjalanan pasti ada rintangannya, Hana. Tapi aku yakin kamu punya kekuatan untuk melewatinya. Dan aku... aku ingin jadi bagian dari perjalanan itu.”\r\n\r\nHana menunduk, wajahnya memerah. Ia merasa perasaan yang selama ini dipendam mulai terbuka, seperti bunga yang perlahan mekar setelah hujan. Namun di balik semua itu, ada juga ketakutan: apakah cinta yang tumbuh di tengah jarak, bahasa, dan budaya yang berbeda ini akan bertahan?\r\n\r\nMalam itu, saat kembali ke asrama, Hana memandang langit yang mulai cerah, bintang-bintang kecil mulai bermunculan. Ia tersenyum sendiri, menyadari bahwa perjalanan cinta dan hidupnya di Seoul baru saja mulai — dengan segala tantangan dan keindahan yang menyertainya.\r\n\r\nHari itu, setelah perbincangan di gazebo, hujan mulai berhenti. Tetesan terakhir menggantung di ujung daun-daun, membiaskan cahaya lampu jalan yang temaram. Hana dan Joon duduk berdampingan, sesekali saling bertukar pandang yang tak perlu diucapkan dengan kata-kata.\r\n\r\n“Kalau aku boleh jujur,” Hana mulai pelan, suaranya hampir tersapu angin, “aku nggak cuma takut gagal di sini. Aku takut kehilangan diriku sendiri.”\r\n\r\nJoon menoleh, matanya menatap dalam penuh perhatian. “Maksudnya?”\r\n\r\n“Aku… aku merasa ada sesuatu yang berubah dalam diriku sejak tiba di sini. Rasanya, aku harus jadi orang yang berbeda. Kadang aku rindu jadi diri aku yang dulu, yang bebas dan nggak penuh beban.”\r\n\r\nJoon mengangguk pelan, “Aku paham. Perubahan itu nggak mudah, Hana. Apalagi di tempat baru, dengan bahasa dan budaya yang berbeda. Tapi, kamu nggak harus kehilangan dirimu sendiri. Kamu cuma sedang belajar menjadi versi terbaik dari dirimu.”\r\n\r\nMereka hening sejenak, ditemani suara riak air dari kolam kecil di dekat gazebo. Hana menutup matanya, mencoba menenangkan hati yang bergejolak.\r\n\r\n“Aku senang kamu ada di sini,” katanya akhirnya, membuka mata dan menatap Joon dengan senyum tipis.\r\n\r\nJoon tersenyum hangat, “Dan aku juga senang bisa ada untuk kamu.”\r\n\r\nMereka berdua bangkit dari bangku, berjalan pelan menuruni tangga kecil menuju jalan setapak yang basah. Langkah mereka seirama, tanpa perlu terburu-buru. Kota Seoul malam itu seolah memberi ruang untuk mereka bernapas dan bermimpi.\r\n\r\nDi tengah jalan, Hana tiba-tiba berhenti dan melihat ke arah Joon. “Joon, kamu pernah nggak sih merasa jatuh cinta tapi juga takut? Takut kalau perasaan itu nggak berbalas, atau kalau semuanya berubah?”\r\n\r\nJoon terdiam sesaat, lalu mengangguk, “Aku pernah. Aku rasa perasaan itu memang rumit. Tapi aku percaya, kalau sesuatu itu memang berarti, dia akan menemukan jalannya.”\r\n\r\nHana merasakan jantungnya berdetak lebih cepat, tapi kali ini bukan karena takut, melainkan karena harapan.\r\n\r\n“Mungkin kita bisa jalani semuanya bersama-sama,” ujarnya lirih.\r\n\r\nJoon menggenggam tangan Hana erat. “Aku setuju. Kita hadapi semuanya bersama.”\r\n\r\nMalam itu, di bawah langit penuh bintang dan udara sejuk musim semi, cinta mereka mulai tumbuh perlahan, tak lagi sekadar angan. Di antara tetesan hujan dan bisikan angin, Hana menemukan sebuah kenyamanan yang tak pernah ia rasakan sebelumnya.'),
(6, 'Cantik Itu Luka', 'Eka Kurniawan', '2002', 1, 'https://i.pinimg.com/736x/b4/39/48/b439481027ddae82a1839bda635320b2.jpg', 'Novel ini mengangkat kisah keluarga di masa penjajahan hingga pasca kemerdekaan, dengan sentuhan sejarah, mitologi, dan realisme magis. Tema utamanya adalah bagaimana kecantikan bisa menjadi kutukan dan membawa luka bagi perempuan, serta eksploitasi dan kekerasan yang mereka alami.', '### Bab 1\r\n\r\nDi sebuah kota kecil bernama Karangjati, lahirlah seorang anak perempuan yang membuat bidan yang menyambutnya tercekat. Bayi itu cantik — terlalu cantik untuk ukuran manusia biasa. Kulitnya putih bersih seperti susu segar, matanya bulat jernih seperti air danau yang tenang, dan bibir mungilnya merah seperti delima. Orang-orang bilang, dia adalah titisan bidadari.\r\n\r\nAnak itu diberi nama Larisa. Ibu kandungnya, seorang perempuan kampung biasa bernama Murni, bahkan merasa takut memeluknya di awal. “Dia terlalu indah untuk jadi anakku,” bisiknya saat pertama kali menatap Larisa. Namun, keindahan itu ternyata bukan anugerah. Sejak kecil, Larisa menarik perhatian — bukan karena prestasi, bukan karena kepribadian, melainkan karena wajahnya yang memukau. Ia seperti lukisan hidup yang selalu mengundang mata, sekaligus niat buruk.\r\n\r\nSaat Larisa berumur 9 tahun, ada tetangga lelaki yang tertangkap mencuri pandang setiap kali Larisa keluar rumah. Ibunya mulai menyadari, bahwa kecantikan anaknya bukan hanya membuat iri, tapi juga menjadi umpan. Murni lalu membatasi Larisa. Tidak boleh keluar saat sore, tidak boleh memakai pakaian terang, tidak boleh tersenyum kepada siapa pun — semua itu demi melindunginya.\r\n\r\nTapi dunia tidak peduli.\r\n\r\nDi sekolah, teman-teman perempuan menjauhi Larisa. Mereka menyebar gosip bahwa Larisa penyihir yang memakai pesona untuk mencuri perhatian. Guru-guru memperlakukannya berbeda. Ada yang bersikap terlalu baik, ada pula yang sengaja menjatuhkannya agar tidak terlalu bersinar. Larisa belajar sejak dini bahwa dunia memuja kecantikan, tetapi juga membencinya dalam waktu bersamaan.\r\n\r\nSuatu hari, saat Larisa berusia 15 tahun, ia pulang dengan wajah kusut. Di gerbang sekolah, seorang guru laki-laki menahannya. Memberinya pujian tentang betisnya yang “terlalu sempurna untuk anak SMA.” Larisa tidak mengerti apakah itu candaan atau bukan, tapi dadanya sesak. Malam itu ia menggunting rambut panjangnya hingga sebahu, mengenakan sweater tiga lapis keesokan harinya, berharap dunia berhenti melihatnya sebagai pajangan.\r\n\r\nNamun tidak semudah itu.\r\n\r\nSemakin ia menutup diri, semakin orang penasaran. Larisa menjadi mitos di sekolahnya: gadis misterius yang selalu tampil kalem dan menunduk, tapi konon punya wajah secantik dewi. Itu justru menambah obsesi. Cowok-cowok mulai menulis namanya di meja. Satu surat cinta ditemukan di loker setiap minggu. Beberapa dengan kata-kata manis. Tapi banyak juga yang menjijikkan, seolah Larisa hanyalah tubuh yang bisa dibayangkan, bukan manusia yang bisa dihargai.\r\n\r\n###  Bab 2\r\nSetelah malam penuh air mata itu, Larisa berubah. Bukan berubah menjadi dingin, bukan juga menjadi pemberontak. Ia hanya belajar menjadi seseorang yang sadar—bahwa hidupnya tidak akan pernah bisa sama seperti orang lain. Ia mulai menciptakan jarak. Bukan untuk menjauh dari dunia, tapi untuk menjaga hatinya tetap utuh.\r\n\r\nDi sekolah, Larisa menjadi lebih pendiam. Bicaranya seperlunya. Ia tak lagi tersenyum sembarangan, karena ia tahu betul senyum perempuan sering disalahartikan sebagai undangan. Ia memilih duduk di bangku paling pojok, dekat jendela, tempat matahari bisa menyentuh wajahnya tapi tidak membuatnya terlihat. Sambil menatap ke luar, ia sering bertanya dalam hati, kenapa Tuhan menciptakan wajah yang disukai banyak orang, tapi membuat pemiliknya kesepian?\r\n\r\nHari-hari terus berlalu, dan luka-luka kecil semakin bertumpuk. Ada saat seorang guru menuduhnya \"menggoda laki-laki\" hanya karena seorang siswa laki-laki berkelahi karena berebut duduk di sampingnya. Ada pula saat sekelompok siswi diam-diam menggunting rok seragamnya di ruang ganti, meninggalkan pesan, “Tutupin aja, Cantik. Bikin risih.”\r\n\r\nLarisa mematung saat melihat roknya yang sobek. Tapi ia tidak menangis. Air matanya sudah terlalu sering keluar. Sekarang, lukanya tak mengeluarkan darah. Ia mulai menyimpannya dalam-dalam—di tempat yang bahkan ia sendiri sulit menjangkaunya.\r\n\r\nNamun, di balik tembok luka itu, ada seseorang yang memperhatikan.\r\n\r\nNamanya Ilham. Murid pindahan dari kota lain, yang duduk satu baris di depan Larisa. Ia tidak pernah mencoba berbasa-basi. Tidak pernah ikut-ikutan menggoda, tidak juga menghindar seperti yang lain. Ia hanya sesekali membalik badan dan menyodorkan penghapus saat Larisa lupa bawa. Atau menyisakan halaman buku saat mereka diminta mencatat hal yang sama.\r\n\r\n“Kenapa kamu nggak pernah ngomong sama aku?” tanya Ilham suatu hari, ketika kelas sedang kosong karena guru belum datang.\r\n\r\nLarisa menoleh pelan, “Karena semua orang yang pernah deket sama aku, akhirnya bikin aku luka.”\r\n\r\nIlham terdiam. Lalu berkata, “Mungkin kamu butuh seseorang yang nggak datang untuk menyembuhkanmu. Tapi untuk tetap ada, bahkan saat kamu belum sembuh.”\r\n\r\nKalimat itu menghentak Larisa lebih dari apa pun. Selama ini, semua orang datang padanya karena ingin menikmati—bukan menemani. Tapi ucapan Ilham terasa… tulus. Bukan karena wajahnya. Tapi karena ia ingin tahu siapa Larisa di balik mata yang menyimpan hujan.\r\n\r\nSejak hari itu, Larisa tidak langsung membuka diri. Tapi ia mulai membuka celah. Ia mulai menulis di buku hariannya lagi. Tentang perasaannya. Tentang rasa takut, marah, dan letih yang tak bisa dibagikan. Ia mulai belajar bahwa luka yang tak berdarah pun tetap butuh diperhatikan.\r\n\r\nDan perlahan, luka-luka itu mulai menemukan ruang untuk bernapas.\r\n\r\nLarisa masih cantik, tentu. Tapi kini, ia mulai menata makna kecantikan itu sendiri. Bahwa cantik bukan tentang wajah, tapi keberanian untuk tetap berdiri meski dijatuhkan berkali-kali. Bahwa cantik bisa jadi luka, tapi juga bisa jadi kekuatan—asal tidak lagi dipakai untuk menyenangkan orang lain, melainkan untuk menyayangi diri sendiri.\r\n\r\n###  Bab 3\r\nPagi itu, langit Karangjati mendung. Tapi untuk pertama kalinya setelah sekian lama, Larisa tersenyum kecil di depan cermin. Bukan karena ia merasa lebih cantik dari kemarin, bukan karena wajahnya terlihat sempurna, tetapi karena ia merasa... kuat.\r\n\r\nDi matanya sendiri, Larisa mulai melihat bukan sekadar bentuk, tapi isi. Cermin yang dulu ia takuti—karena terlalu jujur memperlihatkan wajah yang selalu jadi sumber luka—kini mulai menunjukkan hal yang berbeda: seorang gadis yang pernah jatuh, tapi memilih bangkit.\r\n\r\nSekolah tetap tidak berubah. Tatapan masih datang dari berbagai arah. Tapi Larisa sudah berbeda. Ia tak lagi menghindar, tak lagi menyembunyikan wajah di balik rambut atau sweater. Ia belajar menatap balik, bukan dengan tatapan menantang, tapi dengan mata tenang yang berkata, “Aku bukan boneka.”\r\n\r\nSuatu hari, sekolah mengadakan kegiatan bulanan: Hari Inspirasi. Setiap siswa boleh membagikan cerita, motivasi, atau hal yang bisa menginspirasi teman-temannya. Biasanya, hanya anak-anak populer atau pengurus OSIS yang maju. Tapi kali ini, Larisa mengangkat tangan. Seluruh aula terdiam. Bahkan pembina OSIS sempat terbatuk kaget.\r\n\r\n“Silakan, Larisa,” ucap guru dengan suara pelan.\r\n\r\nLangkah Larisa menuju panggung seperti langkah ke jurang. Jantungnya berdebar. Kakinya nyaris goyah. Tapi Ilham yang duduk di barisan ketiga menatapnya—memberi anggukan pelan. Larisa menarik napas. Mikrofon di tangannya terasa dingin.\r\n\r\n“Aku tahu...” katanya lirih, “...sebagian dari kalian mengenalku karena wajahku. Tapi aku ingin kalian tahu sisi yang lain. Sisi yang tak pernah kalian lihat.”\r\n\r\nSemua mata terpaku padanya.\r\n\r\n“Aku pernah berpikir... cantik itu kutukan. Karena semua yang datang padaku, hanya melihat permukaan. Mereka lupa bahwa aku juga punya rasa takut, marah, bahkan trauma. Ada masa ketika aku ingin merusak wajahku sendiri, hanya supaya dunia berhenti mengganggu.”\r\n\r\nBeberapa orang terkejut. Suara gumaman memenuhi ruangan.\r\n\r\n“Tapi hari ini, aku berdiri di sini bukan untuk menyalahkan. Aku berdiri di sini untuk mengatakan bahwa aku belajar. Bahwa kecantikan bukanlah kutukan kalau kita belajar mengenali diri sendiri. Bahwa siapa pun kita—cantik, biasa saja, berbeda—kita semua pantas dihargai.”\r\n\r\nSuara Larisa mulai mantap.\r\n\r\n“Aku belajar bahwa nilai diri tidak ditentukan dari seberapa sering orang menatap kita. Tapi dari seberapa sering kita menatap diri sendiri dan berkata, ‘Aku cukup.’”\r\n\r\nRuangan hening. Lalu perlahan, satu tepuk tangan terdengar. Disusul yang lain. Dan yang lain lagi.\r\n\r\nItulah pertama kalinya Larisa merasa bebas.\r\n\r\n###  Bab 4\r\nHari-hari setelah Hari Inspirasi berjalan berbeda. Banyak siswa mulai menghampiri Larisa bukan karena penasaran, tapi karena kagum. Beberapa mengucap maaf. Beberapa mengaku bahwa mereka pun punya luka yang selama ini disembunyikan. Larisa mendengarkan semuanya. Dan untuk pertama kalinya, ia tidak merasa sendirian.\r\n\r\nLarisa kini sering terlihat duduk di taman sekolah, membaca buku, menulis di jurnal, atau ngobrol dengan Ilham. Ia tidak lagi menunduk saat berjalan. Ia tidak lagi menahan senyum. Bahkan sesekali, ia tertawa—lepas dan hangat.\r\n\r\nWajahnya masih sama. Cantik. Tapi kini, tatapan orang tak lagi membuatnya terjebak. Ia sudah keluar dari persembunyian. Ia sudah pulang… ke dirinya sendiri.\r\n\r\n###  Bab 5\r\nCinta itu datang perlahan.\r\n\r\nBukan dari gombalan Ilham. Bukan juga dari bunga atau rayuan. Tapi dari hal-hal kecil—cara Ilham meminjamkan jaket saat hujan turun, cara ia mendengarkan saat Larisa bercerita, dan bagaimana ia tak pernah memperlakukan Larisa seolah ia istimewa karena penampilannya, tapi karena keberaniannya.\r\n\r\nSore itu, di bawah langit oranye yang malu-malu, Ilham berkata pelan, “Aku suka kamu, Ris.”\r\n\r\nLarisa terdiam. Dadanya bergetar. Tapi untuk pertama kalinya, ia tidak takut.\r\n\r\nIa tersenyum, “Kamu tahu, dulu aku kira aku nggak pantas dicintai. Tapi sekarang aku tahu, cinta yang sehat bukan tentang siapa yang paling sempurna. Tapi siapa yang mau saling melihat… dan saling menerima.”\r\n\r\nIlham mengangguk pelan. Mereka duduk berdampingan, tak berkata-kata lagi. Tapi hati mereka berbicara.\r\n\r\nLarisa tak lagi hanya gadis yang cantik. Ia adalah gadis yang pernah luka, pernah jatuh, dan kini berdiri, dengan cinta yang tumbuh perlahan… dari keberanian untuk menyembuhkan diri.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Novel'),
(2, 'Komik'),
(3, 'Ilmiah'),
(4, 'Teknologi'),
(5, 'Biografi'),
(6, 'Sejarah'),
(7, 'Fiksi Ilmiah'),
(8, 'Fantasi'),
(9, 'Drama'),
(10, 'Romantis'),
(11, 'Misteri'),
(12, 'Horor'),
(13, 'Self-Help'),
(14, 'Agama'),
(15, 'Puisi'),
(16, 'Ensiklopedia'),
(17, 'Petualangan'),
(18, 'Anak-anak'),
(19, 'Pendidikan'),
(20, 'Kesehatan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_book` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_user`, `id_book`, `rating`, `komentar`, `created_at`) VALUES
(2, 1, 5, 5, 'ceritanya seruu', '2025-05-19 10:43:52'),
(3, 3, 6, 5, 'bagusssss', '2025-05-19 11:39:24'),
(4, 3, 5, 4, 'bagus', '2025-05-19 11:39:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'bunga', 'bunga', 'bunga@gmail.com', '$2y$10$ALBXM4P.AnBE66PZ6/QroufNPhh1OEYrcJgwIEDkSP916rn2dLoIW', 'user', '2025-05-17 19:50:43'),
(2, 'ana', 'ana', 'ana@gmail.com', '$2y$10$2ykw5SHO9u4652GbSAz8dey/weyGYGpPTHA0gWPTXIlB6NvQSLXl.', 'admin', '2025-05-17 19:59:48'),
(3, 'kesya', 'kesya', 'kesya@gmail.com', '$2y$10$3UQWeXZB7G1WLN9O0dkNde/IsB84YXlHGNBsj2f00jm5LZDFExCmK', 'user', '2025-05-19 11:38:37');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD PRIMARY KEY (`id_aktivitas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_book` (`id_book`);

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id_book`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_book` (`id_book`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aktivitas`
--
ALTER TABLE `aktivitas`
  MODIFY `id_aktivitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id_book` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD CONSTRAINT `aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD CONSTRAINT `bookmark_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmark_ibfk_2` FOREIGN KEY (`id_book`) REFERENCES `books` (`id_book`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_book`) REFERENCES `books` (`id_book`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
