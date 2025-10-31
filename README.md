# 🌟 Reviews Plugin - Google Business Yorumları WordPress Eklentisi

Google Business yorumlarınızı WordPress sitenizde modern ve profesyonel bir şekilde görüntüleyin. TrustIndex benzeri şık tasarım!

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)
![PHP](https://img.shields.io/badge/php-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0-green.svg)

## 📋 İçindekiler

- [Özellikler](#özellikler)
- [Kurulum](#kurulum)
- [Google API Ayarları](#google-api-ayarları)
- [Kullanım](#kullanım)
- [Shortcode Parametreleri](#shortcode-parametreleri)
- [Görünüm Stilleri](#görünüm-stilleri)
- [Ekran Görüntüleri](#ekran-görüntüleri)
- [Sık Sorulan Sorular](#sık-sorulan-sorular)
- [Destek](#destek)

## ✨ Özellikler

- ✅ **Google Places API Entegrasyonu** - Otomatik yorum çekme
- ✅ **Akıllı Cache Sistemi** - Ayarlanabilir cache süresi (1-168 saat)
- ✅ **3 Farklı Görünüm Stili**
  - 🎠 Slider (Kaydırmalı)
  - 📊 Grid (Izgara)
  - 📝 List (Liste)
- ✅ **Responsive Tasarım** - Tüm cihazlarda mükemmel görünüm
- ✅ **Modern UI** - TrustIndex benzeri profesyonel tasarım
- ✅ **Yıldız Filtreleme** - Minimum yıldız sayısı belirleme
- ✅ **Yorum Limiti** - Gösterilecek yorum sayısını ayarlama
- ✅ **Kolay Yönetim** - Kullanıcı dostu admin paneli
- ✅ **Güvenlik** - WordPress standartlarına uygun
- ✅ **Çoklu Dil Desteği** - Türkçe dilinde hazır

## 🚀 Kurulum

### Manuel Kurulum

1. Bu repository'yi indirin veya klonlayın:
```bash
git clone https://github.com/elyasoft-bt/Reviews-Plugin.git
```

2. `Reviews-Plugin` klasörünü WordPress kurulumunuzun `wp-content/plugins/` dizinine yükleyin.

3. WordPress admin panelinden **Eklentiler** menüsüne gidin.

4. **Reviews Plugin**'i bulun ve **Etkinleştir** butonuna tıklayın.

5. Sol menüden **Reviews Plugin** sekmesine tıklayarak ayarları yapın.

### WordPress Eklenti Yükleyici ile Kurulum

1. WordPress admin panelinden **Eklentiler > Yeni Ekle** menüsüne gidin.

2. **Eklenti Yükle** butonuna tıklayın.

3. ZIP dosyasını seçin ve **Şimdi Yükle** butonuna tıklayın.

4. Kurulum tamamlandıktan sonra **Eklentiyi Etkinleştir** butonuna tıklayın.

## 🔑 Google API Ayarları

### 1. Google Cloud Console Projesi Oluşturma

1. [Google Cloud Console](https://console.cloud.google.com/) adresine gidin.

2. Yeni bir proje oluşturun:
   - Sol üst köşeden **Proje Seç** > **Yeni Proje** tıklayın
   - Proje adı girin (örn: "My Business Reviews")
   - **Oluştur** butonuna tıklayın

### 2. Places API'yi Etkinleştirme

1. Sol menüden **API ve Hizmetler** > **Kütüphane** seçin.

2. Arama kutusuna **"Places API"** yazın.

3. **Places API**'yi seçin ve **Etkinleştir** butonuna tıklayın.

### 3. API Key Oluşturma

1. Sol menüden **API ve Hizmetler** > **Kimlik Bilgileri** seçin.

2. **Kimlik Bilgileri Oluştur** > **API Anahtarı** seçin.

3. Oluşturulan API anahtarını kopyalayın.

4. **Anahtarı Kısıtla** butonuna tıklayarak güvenlik ayarları yapın:
   - **API kısıtlamaları** bölümünden **Places API**'yi seçin
   - **Kaydet** butonuna tıklayın

### 4. Place ID Bulma

**Yöntem 1: Place ID Finder**

1. [Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id) aracına gidin.

2. İşletmenizin adını veya adresini arayın.

3. Haritadan işletmenizi seçin.

4. **Place ID**'yi kopyalayın.

**Yöntem 2: Google Maps**

1. [Google Maps](https://www.google.com/maps) adresine gidin.

2. İşletmenizi arayın.

3. URL'deki uzun kodu kopyalayın (örn: `ChIJ...`)

### 5. WordPress'te Ayarlama

1. WordPress admin panelinden **Reviews Plugin** menüsüne gidin.

2. **Google API Key** alanına API anahtarınızı yapıştırın.

3. **Google Place ID** alanına Place ID'nizi yapıştırın.

4. Diğer ayarları tercihinize göre yapılandırın:
   - **Cache Süresi**: Yorumların önbellekte tutulma süresi (saat)
   - **Maksimum Yorum Sayısı**: Gösterilecek maksimum yorum (1-50)
   - **Minimum Yıldız**: Gösterilecek minimum yıldız sayısı (1-5)

5. **Değişiklikleri Kaydet** butonuna tıklayın.

## 📝 Kullanım

### Temel Kullanım

Sayfa veya yazı editörüne şu shortcode'u ekleyin:

```
[google_reviews]
```

### Parametreli Kullanım

```
[google_reviews limit="5" style="slider" min_rating="4"]
```

## 🎨 Shortcode Parametreleri

| Parametre | Varsayılan | Açıklama | Değerler |
|-----------|------------|----------|----------|
| `limit` | 10 | Gösterilecek yorum sayısı | 1-50 arası sayı |
| `style` | slider | Görünüm stili | slider, grid, list |
| `min_rating` | 1 | Minimum yıldız sayısı | 1-5 arası sayı |

### Kullanım Örnekleri

**1. Slider Görünümü (Varsayılan)**
```
[google_reviews]
```

**2. Grid Görünümü - 6 Yorum**
```
[google_reviews style="grid" limit="6"]
```

**3. Liste Görünümü - Sadece 5 Yıldızlı**
```
[google_reviews style="list" min_rating="5"]
```

**4. Slider - 8 Yorum, Minimum 4 Yıldız**
```
[google_reviews style="slider" limit="8" min_rating="4"]
```

## 🎭 Görünüm Stilleri

### 1. Slider (Kaydırmalı) 🎠

- Swiper.js ile modern slider
- Otomatik oynatma
- Sağ/Sol ok tuşları
- Responsive tasarım
- Pagination (sayfalama)

```
[google_reviews style="slider"]
```

### 2. Grid (Izgara) 📊

- Kartlar grid sistemi
- Responsive kolon sayısı
  - Desktop: 3 kolon
  - Tablet: 2 kolon
  - Mobil: 1 kolon
- Hover efektleri

```
[google_reviews style="grid"]
```

### 3. List (Liste) 📝

- Dikey liste düzeni
- Tam genişlik kartlar
- Okunması kolay düzen
- Detaylı yorum görüntüleme

```
[google_reviews style="list"]
```

## 📸 Ekran Görüntüleri

### Admin Panel
![Admin Panel](screenshots/admin-panel.png)
*Kullanıcı dostu ayarlar paneli*

### Slider Görünümü
![Slider View](screenshots/slider-view.png)
*Modern kaydırmalı görünüm*

### Grid Görünümü
![Grid View](screenshots/grid-view.png)
*Izgara düzeninde yorumlar*

### Liste Görünümü
![List View](screenshots/list-view.png)
*Liste düzeninde detaylı görünüm*

## 🛠️ Teknik Bilgiler

### Gereksinimler

- **WordPress**: 5.0 veya üzeri
- **PHP**: 7.4 veya üzeri
- **MySQL**: 5.6 veya üzeri

### Dosya Yapısı

```
reviews-plugin/
├── reviews-plugin.php              # Ana eklenti dosyası
├── includes/
│   ├── class-reviews-admin.php     # Admin panel sınıfı
│   ├── class-reviews-api.php       # Google API entegrasyonu
│   └── class-reviews-shortcode.php # Shortcode işleyici
├── assets/
│   ├── css/
│   │   └── reviews-styles.css      # Stil dosyası
│   └── js/
│       └── reviews-script.js       # JavaScript dosyası
├── templates/
│   ├── slider-view.php             # Slider şablonu
│   ├── grid-view.php               # Grid şablonu
│   └── list-view.php               # Liste şablonu
├── screenshots/                     # Ekran görüntüleri
└── README.md                        # Dokümantasyon
```

### Kullanılan Teknolojiler

- **Google Places API** - Yorum verisi
- **Swiper.js** - Slider/Carousel
- **WordPress REST API** - API entegrasyonu
- **Transients API** - Cache yönetimi
- **Settings API** - Ayarlar yönetimi

## ❓ Sık Sorulan Sorular

### Yorumlar gösterilmiyor?

1. API Key ve Place ID'yi kontrol edin
2. Google Cloud Console'da Places API'nin etkin olduğundan emin olun
3. API Key'in kısıtlamalarını kontrol edin
4. Cache'i temizlemeyi deneyin (Admin Panel > Cache Temizle)

### Cache nasıl temizlenir?

**Admin Panel üzerinden:**
1. WordPress admin panelinden **Reviews Plugin** menüsüne gidin
2. Sayfanın altındaki **Cache Temizle** butonuna tıklayın

**Manuel olarak:**
```php
delete_transient('reviews_plugin_cached_data');
```

### API limitleri nelerdir?

Google Places API limitleri:
- **Ücretsiz**: Ayda $200 kredi (yaklaşık 28,000 istek)
- **Ücretli**: İstek başına ücretlendirme

Cache sistemi sayesinde API istekleri minimize edilir.

### Birden fazla işletme yorumu gösterebilir miyim?

Evet! Her işletme için farklı shortcode kullanabilirsiniz:

```
[google_reviews place_id="İŞLETME_1_PLACE_ID"]
[google_reviews place_id="İŞLETME_2_PLACE_ID"]
```

*Not: Bu özellik gelecek versiyonda eklenecektir.*

### Hangi diller destekleniyor?

Şu anda Türkçe dil desteği mevcuttur. Yorumlar Google'dan dil ayarına göre gelir.

### Yorum metinleri çok uzun görünüyor?

JavaScript otomatik olarak 200 karakterden uzun yorumları kısaltır. Kullanıcılar tıklayarak tam metni görebilir.

### Mobil uyumlu mu?

Evet! Plugin tamamen responsive tasarıma sahiptir ve tüm cihazlarda mükemmel görünür.

## 🔧 Gelişmiş Kullanım

### Custom CSS Ekleme

Tema CSS'inize özel stiller ekleyebilirsiniz:

```css
/* Özel renk şeması */
.reviews-plugin-wrapper {
    --primary-color: #your-color;
}

/* Yıldız rengi değiştirme */
.star-full {
    color: #ff6b35;
}

/* Kart tasarımını değiştirme */
.review-card {
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}
```

### PHP ile Kullanım

Template dosyalarınızda PHP ile kullanabilirsiniz:

```php
<?php echo do_shortcode('[google_reviews style="grid" limit="6"]'); ?>
```

### Filtre ve Hook'lar

```php
// Yorum verilerini filtreleme
add_filter('reviews_plugin_data', function($data) {
    // Özel işlemler
    return $data;
});

// Cache süresini programatik olarak değiştirme
add_filter('reviews_plugin_cache_duration', function($duration) {
    return 48; // 48 saat
});
```

## 🐛 Hata Ayıklama

### Debug Modunu Açma

`wp-config.php` dosyasına ekleyin:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Hatalar `wp-content/debug.log` dosyasına kaydedilir.

### Yaygın Hatalar

**API Error: REQUEST_DENIED**
- API Key kısıtlamalarını kontrol edin
- Places API'nin etkin olduğundan emin olun

**No reviews found**
- Place ID'nin doğru olduğundan emin olun
- İşletmenizin yorumları olup olmadığını kontrol edin

## 🤝 Katkıda Bulunma

Katkılarınızı bekliyoruz! 

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add some AmazingFeature'`)
4. Branch'inizi push edin (`git push origin feature/AmazingFeature`)
5. Pull Request açın

## 📄 Lisans

Bu proje GPL v2 veya üzeri lisans altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

## 👨‍💻 Geliştirici

**Elyasoft**
- GitHub: [@elyasoft-bt](https://github.com/elyasoft-bt)
- Website: [elyasoft.com](https://elyasoft.com)

## 📞 Destek

Sorunlarınız veya sorularınız için:

- 🐛 [Issue açın](https://github.com/elyasoft-bt/Reviews-Plugin/issues)
- 💬 [Discussions](https://github.com/elyasoft-bt/Reviews-Plugin/discussions)
- 📧 Email: info@elyasoft.com

## 🎉 Teşekkürler

- Google Places API
- Swiper.js
- WordPress Community

## 📌 Yol Haritası

- [ ] Çoklu işletme desteği
- [ ] Özel tasarım şablonları
- [ ] Widget desteği
- [ ] Gutenberg block
- [ ] Elementor widget
- [ ] WPML/Polylang entegrasyonu
- [ ] Yorum moderasyonu
- [ ] Analytics ve istatistikler

---

⭐ Bu projeyi beğendiyseniz yıldız vermeyi unutmayın!

**Son Güncelleme**: 31 Ekim 2025
**Versiyon**: 1.0.0
