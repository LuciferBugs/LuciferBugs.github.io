import express from 'express';
import axios from 'axios';

const app = express();
const port = 3031;
const url = 'https://time-api.prabowo.zip/';

const updateUserTime = async () => {
  try {
    const response = await axios.post(`${url}update.php`);

    console.log('Response from PHP:', response.data);
  } catch (error) {
    console.error('Error updating time:', error.message);
  }
};

app.listen(port, () => {
  console.log(`Server running on port ${port}`);

  setInterval(updateUserTime, 1000);
});
