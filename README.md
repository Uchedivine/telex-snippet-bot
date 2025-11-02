# Telex Code Snippet Manager Bot

A powerful code snippet manager bot for Telex that helps developers save, organize, and retrieve code snippets directly in their conversations.

## 🚀 Features

- **Save Snippets**: Store code snippets with language tags
- **Retrieve Snippets**: Get snippets by programming language
- **List Library**: View all your saved snippets organized by language
- **Delete Snippets**: Remove snippets you no longer need
- **Multi-language Support**: Works with any programming language

## 📋 Commands

| Command | Description | Example |
|---------|-------------|---------|
| `/help` | Show available commands | `/help` |
| `/save <language> <code>` | Save a code snippet | `/save php echo 'Hello';` |
| `/list` | List all your snippets | `/list` |
| `/get <language>` | Get snippets by language | `/get javascript` |
| `/delete <id>` | Delete a snippet | `/delete 1` |

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: SQLite
- **Deployment**: Railway
- **Integration**: Telex.im API

## 📦 Installation

### Prerequisites
- PHP 8.2+
- Composer
- SQLite

### Local Setup

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/telex-snippet-bot.git
cd telex-snippet-bot
```

2. Install dependencies:
```bash
composer install
```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database:
```bash
touch database/database.sqlite
php artisan migrate
```

5. Run the server:
```bash
php artisan serve
```

## 🌐 API Endpoints

### Webhook Endpoint
```
POST /api/telex/webhook
```

**Request Body:**
```json
{
    "message": "/save php echo 'test';",
    "user_id": "user123",
    "channel_id": "channel456"
}
```

**Response:**
```json
{
    "status": "success"
}
```

### Health Check
```
GET /api/health
```

## 🚢 Deployment

Deployed on Railway: https://web-production-6a761.up.railway.app

### Deploy Your Own

1. Fork this repository
2. Sign up at [Railway](https://railway.app)
3. Create new project from GitHub repo
4. Add environment variables:
   - `APP_KEY`: Generate with `php artisan key:generate --show`
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `DB_CONNECTION`: `sqlite`
5. Deploy!

## 🔗 Telex Integration

### Workflow JSON
```json
{
  "active": true,
  "category": "utilities",
  "description": "A code snippet manager bot for developers",
  "id": "snippet_manager_bot",
  "name": "Code Snippet Manager",
  "nodes": [{
    "id": "snippet_bot",
    "name": "Snippet Bot",
    "type": "a2a/mastra-a2a-node",
    "url": "https://web-production-6a761.up.railway.app/api/telex/webhook"
  }]
}
```

## 📝 How It Works

1. User sends a command in Telex
2. Telex forwards the message to our webhook
3. Bot processes the command and interacts with SQLite database
4. Response is sent back to Telex
5. User sees the result in their chat

## 🧪 Testing

Test locally with Thunder Client or Postman:

**Example Request:**
```bash
POST http://localhost:8000/api/telex/webhook
Content-Type: application/json

{
    "message": "/help",
    "user_id": "test123",
    "channel_id": "test"
}
```

## 📊 Database Schema

**Snippets Table:**
- `id`: Primary key
- `user_id`: Telex user identifier
- `language`: Programming language
- `code`: The code snippet
- `description`: Optional description
- `channel_id`: Telex channel identifier
- `timestamps`: Created/updated timestamps

## 🤝 Contributing

Contributions welcome! Please open an issue or submit a pull request.

## 📄 License

MIT License

## 👤 Author

**Your Name**
- GitHub: @uchedivine/ https://github.com/Uchedivine
- Email: uchedivine65@gmail.com

## 🙏 Acknowledgments

- Built for Telex.im Stage 3 Backend Challenge
- Powered by Laravel and Railway

---

Made with ❤️ for developers who love to code