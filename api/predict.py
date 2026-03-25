from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle
import numpy as np
import json
import os

app = Flask(__name__)
CORS(app)  # Izinkan request dari PHP

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load model, scaler, encoding map saat startup
try:
    with open(os.path.join(BASE_DIR, 'smarthealth_model.pkl'), 'rb') as f:
        model = pickle.load(f)
    with open(os.path.join(BASE_DIR, 'smarthealth_scaler.pkl'), 'rb') as f:
        scaler = pickle.load(f)
    with open(os.path.join(BASE_DIR, 'encoding_map.json'), 'r') as f:
        encoding_map = json.load(f)
    print("✅ Model, scaler, dan encoding map berhasil dimuat!")
except FileNotFoundError as e:
    print(f"❌ File tidak ditemukan: {e}")
    print("Pastikan smarthealth_model.pkl, smarthealth_scaler.pkl, encoding_map.json ada di folder api/")
    model = scaler = encoding_map = None


@app.route('/health', methods=['GET'])
def health():
    """Endpoint cek status API"""
    return jsonify({
        'status': 'ok',
        'model_loaded': model is not None,
        'message': 'SmartHealth Flask API berjalan!'
    })


@app.route('/predict', methods=['POST'])
def predict():
    """Endpoint prediksi risiko diabetes"""
    if model is None:
        return jsonify({'error': 'Model belum dimuat'}), 500

    data = request.get_json()
    if not data:
        return jsonify({'error': 'Data tidak valid'}), 400

    required = ['gender', 'age', 'hypertension', 'heart_disease',
                'smoking_history', 'bmi', 'hba1c_level', 'blood_glucose_level']
    
    missing = [f for f in required if f not in data]
    if missing:
        return jsonify({'error': f'Field kurang: {missing}'}), 400

    try:
        # Encoding
        gender_enc  = encoding_map['gender'].get(data['gender'], 0)
        smoking_enc = encoding_map['smoking_history'].get(data['smoking_history'], 0)

        # Susun input array sesuai urutan training
        # ['gender','age','hypertension','heart_disease','smoking_history','bmi','HbA1c_level','blood_glucose_level']
        input_arr = np.array([[
            gender_enc,
            float(data['age']),
            int(data['hypertension']),
            int(data['heart_disease']),
            smoking_enc,
            float(data['bmi']),
            float(data['hba1c_level']),
            int(data['blood_glucose_level'])
        ]])

        # Scaling
        input_scaled = scaler.transform(input_arr)

        # Prediksi
        prediction   = int(model.predict(input_scaled)[0])
        probabilities = model.predict_proba(input_scaled)[0].tolist()
        prob_diabetes = probabilities[1]

        # Risk level
        prob_pct = prob_diabetes * 100
        if prob_pct < 20:
            risk_level = 'Rendah'
        elif prob_pct < 50:
            risk_level = 'Sedang'
        elif prob_pct < 75:
            risk_level = 'Tinggi'
        else:
            risk_level = 'Sangat Tinggi'

        return jsonify({
            'prediction':           prediction,
            'prediction_label':     'Diabetes' if prediction == 1 else 'Non-Diabetes',
            'probability_diabetes': round(prob_diabetes, 4),
            'probability_normal':   round(probabilities[0], 4),
            'risk_level':           risk_level,
            'confidence':           round(max(probabilities) * 100, 2)
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    print("🚀 SmartHealth Flask API starting...")
    print("📡 Endpoint: http://localhost:5000/predict")
    print("❤️  Health check: http://localhost:5000/health")
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)