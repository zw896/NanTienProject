package digital5.nantien.Activity;

import android.content.Intent;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import digital5.nantien.R;

public class RegisterActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);


        final EditText username = (EditText) findViewById(R.id.username_text);
        final EditText password = (EditText) findViewById(R.id.password_text);
        final EditText confirm_pw = (EditText) findViewById(R.id.confirmpw_text);
        final EditText emailtext = (EditText) findViewById(R.id.email_text);
        final Spinner gender = (Spinner) findViewById(R.id.spinner);
        final Button registerButton = (Button) findViewById(R.id.register_button);


        registerButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                final String name = username.getText().toString();
                final String pw = password.getText().toString();
                final String confirm_pw = password.getText().toString();
                final String email = emailtext.getText().toString();
                final String gender_value = String.valueOf(gender.getSelectedItem());


                Response.Listener<String> responseListener = new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONObject jsonResponse = new JSONObject(response);
                            String hasError;

//                            boolean success = jsonResponse.getBoolean("success");
//
//                            if(success){
//                                Toast.makeText(RegisterActivity.this, "Registration Success!", Toast.LENGTH_LONG).show();
//                                Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
//                                RegisterActivity.this.startActivity(intent);
//
//                            }else{
//                                AlertDialog.Builder builder = new AlertDialog.Builder(RegisterActivity.this);
//                                builder.setMessage("Server Error!")
//                                        .setNegativeButton("Retry", null)
//                                        .create()
//                                        .show();
//                            }




                                hasError = jsonResponse.getString("status");

                                if(hasError.equals("fail")){
                                    AlertDialog.Builder builder = new AlertDialog.Builder(RegisterActivity.this);
                                    builder.setMessage("Error! User already exists")
                                            .setNegativeButton("Retry", null)
                                            .create()
                                            .show();

                                }else{
                                    Toast.makeText(RegisterActivity.this, "Registration Success!", Toast.LENGTH_LONG).show();
                                    Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
                                    RegisterActivity.this.startActivity(intent);
                                }




                        } catch (JSONException e) {
                            e.printStackTrace();

                        }

                    }
                };

                RegisterRequest registerRequest = new RegisterRequest(email, name, pw, confirm_pw, 1, responseListener);
                RequestQueue queue = Volley.newRequestQueue(RegisterActivity.this);
                queue.add(registerRequest);
            }
        });

    }
}
