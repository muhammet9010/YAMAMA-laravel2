@php
$containerFooter = (isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
      <strong >ACIT &copy; 2005-2024 <a href="https://acwad-it.com/">ACWAD</a>.</strong>ACWAD.

    </div>
  </div>
</footer>
<!--/ Footer-->
