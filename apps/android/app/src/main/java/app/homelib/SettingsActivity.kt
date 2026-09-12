package app.homelib

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import app.homelib.databinding.ActivitySettingsBinding

class SettingsActivity : AppCompatActivity() {
    private lateinit var binding: ActivitySettingsBinding
    private lateinit var store: ServerStore

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivitySettingsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        store = ServerStore(this)
        binding.serverUrl.setText(store.url().ifBlank { "http://" })
        binding.serverUrl.setSelection(binding.serverUrl.text?.length ?: 0)
        binding.serverUrl.requestFocus()

        binding.saveButton.setOnClickListener { save() }
    }

    private fun save() {
        val url = store.save(binding.serverUrl.text?.toString().orEmpty())

        if (url.isBlank()) {
            Toast.makeText(this, R.string.server_required, Toast.LENGTH_SHORT).show()
            return
        }

        startActivity(Intent(this, MainActivity::class.java).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP))
        finish()
    }

    companion object {
        fun start(context: Context) {
            context.startActivity(Intent(context, SettingsActivity::class.java))
        }
    }
}
