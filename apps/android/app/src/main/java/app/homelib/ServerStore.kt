package app.homelib

import android.content.Context

class ServerStore(context: Context) {
    private val prefs = context.getSharedPreferences(PREFS, Context.MODE_PRIVATE)

    fun url(): String = prefs.getString(KEY_URL, "").orEmpty()

    fun hasUrl(): Boolean = url().isNotBlank()

    fun save(raw: String): String {
        val normalized = normalize(raw)
        prefs.edit().putString(KEY_URL, normalized).apply()
        return normalized
    }

    companion object {
        private const val PREFS = "homelib"
        private const val KEY_URL = "server_url"

        fun normalize(raw: String): String {
            var value = raw.trim().trimEnd('/')

            if (value.isEmpty()) {
                return ""
            }

            if (!value.startsWith("http://") && !value.startsWith("https://")) {
                value = "http://$value"
            }

            return value
        }
    }
}
