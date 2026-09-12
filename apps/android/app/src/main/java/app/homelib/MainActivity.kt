package app.homelib

import android.annotation.SuppressLint
import android.app.UiModeManager
import android.content.res.Configuration
import android.graphics.Bitmap
import android.os.Bundle
import android.view.KeyEvent
import android.view.View
import android.webkit.WebChromeClient
import android.webkit.WebResourceError
import android.webkit.WebResourceRequest
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import android.widget.FrameLayout
import androidx.activity.OnBackPressedCallback
import androidx.appcompat.app.AppCompatActivity
import app.homelib.databinding.ActivityMainBinding

class MainActivity : AppCompatActivity() {
    private lateinit var binding: ActivityMainBinding
    private lateinit var store: ServerStore
    private var customView: View? = null
    private var customViewCallback: WebChromeClient.CustomViewCallback? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        store = ServerStore(this)

        if (!store.hasUrl()) {
            SettingsActivity.start(this)
            finish()
            return
        }

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupWebView()
        binding.retryButton.setOnClickListener { loadLibrary() }
        binding.settingsButton.setOnClickListener { SettingsActivity.start(this) }
        binding.errorSettingsButton.setOnClickListener { SettingsActivity.start(this) }

        onBackPressedDispatcher.addCallback(
            this,
            object : OnBackPressedCallback(true) {
                override fun handleOnBackPressed() {
                    when {
                        customView != null -> hideCustomView()
                        binding.webView.canGoBack() -> binding.webView.goBack()
                        else -> finish()
                    }
                }
            },
        )

        loadLibrary()
    }

    override fun onKeyDown(keyCode: Int, event: KeyEvent?): Boolean {
        if (keyCode == KeyEvent.KEYCODE_MENU || keyCode == KeyEvent.KEYCODE_BUTTON_Y) {
            SettingsActivity.start(this)
            return true
        }

        return super.onKeyDown(keyCode, event)
    }

    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        binding.webView.settings.apply {
            javaScriptEnabled = true
            domStorageEnabled = true
            mediaPlaybackRequiresUserGesture = false
            mixedContentMode = WebSettings.MIXED_CONTENT_COMPATIBILITY_MODE
            userAgentString = "${settings.userAgentString} HomelibApp/1.0 ${deviceKind()}"
        }

        binding.webView.webViewClient = object : WebViewClient() {
            override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
                showWeb()
                view?.requestFocus()
            }

            override fun onReceivedError(
                view: WebView?,
                request: WebResourceRequest?,
                error: WebResourceError?,
            ) {
                if (request?.isForMainFrame == true) {
                    showError()
                }
            }
        }

        binding.webView.webChromeClient = object : WebChromeClient() {
            override fun onShowCustomView(view: View, callback: CustomViewCallback) {
                if (customView != null) {
                    callback.onCustomViewHidden()
                    return
                }

                customView = view
                customViewCallback = callback
                binding.fullscreenContainer.visibility = View.VISIBLE
                binding.fullscreenContainer.addView(
                    view,
                    FrameLayout.LayoutParams(
                        FrameLayout.LayoutParams.MATCH_PARENT,
                        FrameLayout.LayoutParams.MATCH_PARENT,
                    ),
                )
                binding.webView.visibility = View.GONE
                binding.settingsButton.visibility = View.GONE
            }

            override fun onHideCustomView() {
                hideCustomView()
            }
        }
    }

    private fun loadLibrary() {
        showWeb()
        binding.webView.requestFocus()
        binding.webView.loadUrl(store.url())
    }

    private fun showWeb() {
        binding.webView.visibility = View.VISIBLE
        binding.errorPanel.visibility = View.GONE
    }

    private fun showError() {
        binding.webView.visibility = View.GONE
        binding.errorPanel.visibility = View.VISIBLE
        binding.errorSettingsButton.requestFocus()
    }

    private fun hideCustomView() {
        binding.fullscreenContainer.removeAllViews()
        binding.fullscreenContainer.visibility = View.GONE
        binding.webView.visibility = View.VISIBLE
        binding.settingsButton.visibility = View.VISIBLE
        customViewCallback?.onCustomViewHidden()
        customView = null
        customViewCallback = null
    }

    private fun deviceKind(): String {
        val uiMode = getSystemService(UI_MODE_SERVICE) as UiModeManager

        return if (uiMode.currentModeType == Configuration.UI_MODE_TYPE_TELEVISION) {
            "TV"
        } else {
            "Mobile"
        }
    }
}
