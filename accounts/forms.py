from django import forms
from django.contrib.auth.forms import ReadOnlyPasswordHashField
from .models import CustomUser



class UserCreationForm(forms.ModelForm):
    password1 = forms.CharField(label='Password', widget=forms.PasswordInput)
    password2 = forms.CharField(label='Confirm Password', widget=forms.PasswordInput)

    class Meta:
        model = CustomUser
        fields = ['email', 'FirstName', 'LastName', 'picture']

    def clean_password2(self):
        """Check that the two password entries match."""
        p1 = self.cleaned_data.get('password1')
        p2 = self.cleaned_data.get('password2')
        if p1 and p2 and p1 != p2:
            raise forms.ValidationError("Passwords do not match.")
        return p2

    def save(self, commit=True):
        """Hash the password and save the user."""
        user = super().save(commit=False)
        user.set_password(self.cleaned_data['password1'])  # ✅ hash the password
        if commit:
            user.save()
        return user


class UserLoginForm(forms.Form):
    email = forms.EmailField()
    password = forms.CharField(widget=forms.PasswordInput)



class ProfileUpdateForm(forms.ModelForm):
    class Meta:
        model = CustomUser
        fields = ['email', 'FirstName','LastName','picture']